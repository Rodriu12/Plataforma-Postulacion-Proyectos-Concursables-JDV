<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class DocumentoOcrService
{
    public function validarDocumento(string $rutaPdf, array $datos): array
    {
        if (! is_file($rutaPdf)) {
            return [
                'aprobado' => false,
                'texto' => '',
                'motivo' => 'No se encontró el archivo PDF.',
            ];
        }

        $texto = $this->extraerTextoDesdePdf($rutaPdf);

        if (blank($texto)) {
            return [
                'aprobado' => false,
                'texto' => '',
                'motivo' => 'El PDF no pudo leerse o no contiene texto legible.',
            ];
        }

        $textoNormalizado = $this->normalizarTexto($texto);
        $nombre = $this->normalizarTexto((string) ($datos['nombre'] ?? ''));
        $direccion = (string) ($datos['direccion'] ?? '');
        $sector = (string) ($datos['sector'] ?? '');

        // Validación de nombre (completo o parcial con palabras clave)
        $tieneNombre = filled($nombre) && (
            str_contains($textoNormalizado, $nombre) ||
            $this->coincideNombreParcial($textoNormalizado, $nombre)
        );

        // Validación de dirección
        $tieneDireccion = $this->coincideDireccion($textoNormalizado, $direccion, $sector);

        if ($tieneNombre && $tieneDireccion) {
            return [
                'aprobado' => true,
                'texto' => $texto,
                'motivo' => 'El comprobante coincide con el nombre y la dirección del vecino.',
            ];
        }

        if ($tieneNombre && ! $tieneDireccion) {
            return [
                'aprobado' => false,
                'texto' => $texto,
                'motivo' => "El comprobante coincide con el nombre ({$datos['nombre']}), pero la dirección registrada ({$direccion}) no coincide con el documento.",
            ];
        }

        if (! $tieneNombre && $tieneDireccion) {
            return [
                'aprobado' => false,
                'texto' => $texto,
                'motivo' => "El comprobante coincide con la dirección, pero el nombre registrado ({$datos['nombre']}) no coincide con el documento.",
            ];
        }

        return [
            'aprobado' => false,
            'texto' => $texto,
            'motivo' => 'El texto del comprobante no coincide con el nombre ni con la dirección registrada del vecino.',
        ];
    }

    public function extraerTextoDesdePdf(string $rutaPdf): string
    {
        // 1. Intentar extracción directa mediante pdftotext (ultra rápido para PDFs digitales)
        $pdftotext = $this->resolverRutaBinario('pdftotext');
        if ($pdftotext) {
            $proceso = new Process([$pdftotext, '-layout', $rutaPdf, '-']);
            $proceso->run();
            if ($proceso->isSuccessful() && filled(trim($proceso->getOutput()))) {
                return trim($proceso->getOutput());
            }
        }

        // 2. Extracción mediante pdftoppm + tesseract (para PDFs escaneados o imágenes)
        $pdftoppm = $this->resolverRutaBinario('pdftoppm');
        $tesseract = $this->resolverRutaBinario('tesseract');

        if ($pdftoppm && $tesseract) {
            $tmpDir = sys_get_temp_dir() . '/vecindar_ocr_' . uniqid('', true);
            if (is_dir($tmpDir) || mkdir($tmpDir, 0777, true) || is_dir($tmpDir)) {
                $convertir = new Process([
                    $pdftoppm,
                    '-png',
                    '-r',
                    '300',
                    $rutaPdf,
                    $tmpDir . '/page',
                ]);

                $convertir->run();

                if ($convertir->isSuccessful()) {
                    $imagenes = glob($tmpDir . '/page*.png');
                    $fragmentos = [];

                    foreach ($imagenes ?: [] as $imagen) {
                        $ocr = new Process([
                            $tesseract,
                            $imagen,
                            'stdout',
                            '--psm',
                            '6',
                        ]);

                        $ocr->run();

                        if ($ocr->isSuccessful()) {
                            $fragmentos[] = trim($ocr->getOutput());
                        }
                    }

                    $this->limpiarDir($tmpDir);

                    if (! empty($fragmentos)) {
                        return trim(implode("\n", array_filter($fragmentos, fn ($f) => filled($f))));
                    }
                } else {
                    $this->limpiarDir($tmpDir);
                }
            }
        }

        // 3. Respaldo nativo de PHP: lectura directa de streams de texto en el PDF
        return $this->extraerTextoNativoPhp($rutaPdf);
    }

    protected function extraerTextoNativoPhp(string $rutaPdf): string
    {
        $contenido = @file_get_contents($rutaPdf);
        if ($contenido === false || $contenido === '') {
            return '';
        }

        $texto = '';

        // Buscar bloques comprimidos con FlateDecode
        if (preg_match_all('/stream[\r\n]+(.*?)[\r\n]+endstream/s', $contenido, $streams)) {
            foreach ($streams[1] as $stream) {
                $descomprimido = @gzuncompress($stream);
                if ($descomprimido !== false) {
                    if (preg_match_all('/\((.*?)\)\s*T[jJ]/', $descomprimido, $matches)) {
                        $texto .= ' ' . implode(' ', $matches[1]);
                    }
                    if (preg_match_all('/\[(.*?)\]\s*TJ/', $descomprimido, $matches)) {
                        foreach ($matches[1] as $arrayMatch) {
                            if (preg_match_all('/\((.*?)\)/', $arrayMatch, $subMatches)) {
                                $texto .= ' ' . implode('', $subMatches[1]);
                            }
                        }
                    }
                }
            }
        }

        // Buscar texto sin comprimir
        if (preg_match_all('/\((.*?)\)\s*T[jJ]/', $contenido, $matches)) {
            $texto .= ' ' . implode(' ', $matches[1]);
        }

        return trim(preg_replace('/\s+/', ' ', $texto));
    }

    public function normalizarTexto(string $valor): string
    {
        $valor = mb_strtolower($valor, 'UTF-8');
        $valor = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'],
            ['a', 'e', 'i', 'o', 'u', 'n', 'u'],
            $valor,
        );
        $valor = preg_replace('/[^a-z0-9\s]/', ' ', $valor) ?? $valor;
        $valor = preg_replace('/\s+/', ' ', trim($valor)) ?? '';

        return $valor;
    }

    protected function coincideNombreParcial(string $textoDoc, string $nombre): bool
    {
        $palabras = array_filter(explode(' ', $nombre), fn ($p) => strlen($p) >= 3);
        if (count($palabras) < 2) {
            return false;
        }

        $encontradas = 0;
        foreach ($palabras as $palabra) {
            if (str_contains($textoDoc, $palabra)) {
                $encontradas++;
            }
        }

        // Si contiene al menos 2 palabras del nombre (ej: Nombre + Apellido)
        return $encontradas >= min(2, count($palabras));
    }

    protected function coincideDireccion(string $textoDoc, string $direccion, string $sector = ''): bool
    {
        $dir = $this->normalizarTexto($direccion);
        $sec = $this->normalizarTexto($sector);

        if ($dir !== '' && str_contains($textoDoc, $dir)) {
            return true;
        }

        // Extraer números (de casa / departamento)
        preg_match_all('/\b\d+\b/', $dir, $matchesNum);
        $numerosDir = $matchesNum[0] ?? [];

        // Palabras de calle (excluyendo conectores y tipos de vía comunes)
        $palabrasOmitir = ['calle', 'pasaje', 'av', 'avenida', 'depto', 'departamento', 'casa', 'n', 'no', 'numero', 'villa', 'poblacion', 'los', 'las', 'el', 'la', 'del'];
        $palabrasDir = array_filter(explode(' ', $dir), fn ($w) => strlen($w) >= 3 && ! in_array($w, $palabrasOmitir) && ! is_numeric($w));

        // Si tiene palabras de calle y número, verificar que ambos aparezcan en el documento
        if (! empty($palabrasDir) && ! empty($numerosDir)) {
            $tieneCalle = false;
            foreach ($palabrasDir as $p) {
                if (str_contains($textoDoc, $p)) {
                    $tieneCalle = true;
                    break;
                }
            }

            $tieneNumero = false;
            foreach ($numerosDir as $num) {
                if (preg_match('/\b' . preg_quote($num, '/') . '\b/', $textoDoc)) {
                    $tieneNumero = true;
                    break;
                }
            }

            if ($tieneCalle && $tieneNumero) {
                return true;
            }
        }

        if ($sec !== '' && str_contains($textoDoc, $sec)) {
            return true;
        }

        return false;
    }

    public function binarioDisponible(string $binario): bool
    {
        return $this->resolverRutaBinario($binario) !== null;
    }

    public function resolverRutaBinario(string $binario): ?string
    {
        $binarioLimpio = strtolower(trim($binario));

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $candidatos = match ($binarioLimpio) {
                'tesseract' => [
                    'C:/Program Files/Tesseract-OCR/tesseract.exe',
                    'C:/Program Files (x86)/Tesseract-OCR/tesseract.exe',
                    'C:/tools/Tesseract-OCR/tesseract.exe',
                    'C:/tools/tesseract/tesseract.exe',
                ],
                'pdftoppm' => [
                    'C:/texlive/2026/bin/windows/pdftoppm.exe',
                    'C:/Program Files/poppler/Library/bin/pdftoppm.exe',
                    'C:/Program Files (x86)/poppler/Library/bin/pdftoppm.exe',
                    'C:/tools/poppler/Library/bin/pdftoppm.exe',
                    'C:/Users/rodrigo.DESKTOP-2PHUO8N/Desktop/TD/Release-26.09.0-0/poppler-26.09.0/Library/bin/pdftoppm.exe',
                ],
                'pdftotext' => [
                    'C:/texlive/2026/bin/windows/pdftotext.exe',
                    'C:/Program Files/poppler/Library/bin/pdftotext.exe',
                    'C:/Program Files (x86)/poppler/Library/bin/pdftotext.exe',
                    'C:/tools/poppler/Library/bin/pdftotext.exe',
                ],
                default => [],
            };

            foreach ($candidatos as $ruta) {
                if (is_file($ruta)) {
                    return $ruta;
                }
            }
        }

        $path = getenv('PATH');
        if (is_string($path)) {
            foreach (explode(PATH_SEPARATOR, $path) as $directorio) {
                $directorio = trim((string) $directorio);
                if ($directorio === '') {
                    continue;
                }

                foreach ([$binarioLimpio, $binarioLimpio . '.exe'] as $nombre) {
                    $ruta = rtrim($directorio, '\\/') . DIRECTORY_SEPARATOR . $nombre;
                    if (is_file($ruta)) {
                        return $ruta;
                    }
                }
            }
        }

        return null;
    }

    protected function limpiarDir(string $rutaDir): void
    {
        if (! is_dir($rutaDir)) {
            return;
        }

        foreach (glob($rutaDir . '/*') ?: [] as $archivo) {
            @unlink($archivo);
        }

        @rmdir($rutaDir);
    }
}
