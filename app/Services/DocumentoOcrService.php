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

        $binarios = ['pdftoppm', 'tesseract'];
        foreach ($binarios as $binario) {
            if (! $this->binarioDisponible($binario)) {
                return [
                    'aprobado' => null,
                    'texto' => '',
                    'motivo' => "La herramienta local {$binario} no está instalada en el servidor.",
                ];
            }
        }

        $texto = $this->extraerTextoDesdePdf($rutaPdf);

        if (blank($texto)) {
            return [
                'aprobado' => false,
                'texto' => '',
                'motivo' => 'El PDF no pudo leerse con OCR o no contiene texto legible.',
            ];
        }

        $textoNormalizado = $this->normalizarTexto($texto);
        $nombre = $this->normalizarTexto((string) ($datos['nombre'] ?? ''));
        $direccion = $this->normalizarTexto((string) ($datos['direccion'] ?? ''));
        $sector = $this->normalizarTexto((string) ($datos['sector'] ?? ''));

        $tieneNombre = filled($nombre) && str_contains($textoNormalizado, $nombre);
        $tieneDireccion = false;

        if (filled($direccion)) {
            $tieneDireccion = str_contains($textoNormalizado, $direccion);
        }

        if (! $tieneDireccion && filled($sector)) {
            $tieneDireccion = str_contains($textoNormalizado, $sector);
        }

        if ($tieneNombre && $tieneDireccion) {
            return [
                'aprobado' => true,
                'texto' => $texto,
                'motivo' => 'El comprobante coincide con el nombre y la dirección del vecino.',
            ];
        }

        return [
            'aprobado' => false,
            'texto' => $texto,
            'motivo' => 'El texto OCR no coincide con el nombre o la dirección registrada del vecino.',
        ];
    }

    public function extraerTextoDesdePdf(string $rutaPdf): string
    {
        $tmpDir = sys_get_temp_dir() . '/vecindar_ocr_' . uniqid('', true);
        if (! is_dir($tmpDir) && ! mkdir($tmpDir, 0777, true) && ! is_dir($tmpDir)) {
            return '';
        }

        $convertir = new Process([
            'pdftoppm',
            '-png',
            '-r',
            '300',
            $rutaPdf,
            $tmpDir . '/page',
        ]);

        $convertir->run();

        if (! $convertir->isSuccessful()) {
            $this->limpiarDir($tmpDir);

            return '';
        }

        $imagenes = glob($tmpDir . '/page*.png');
        $fragmentos = [];

        foreach ($imagenes ?: [] as $imagen) {
            $ocr = new Process([
                'tesseract',
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

        return trim(implode("\n", array_filter($fragmentos, fn ($fragmento) => filled($fragmento))));
    }

    protected function normalizarTexto(string $valor): string
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

    protected function binarioDisponible(string $binario): bool
    {
        $comando = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
            ? ['where', $binario]
            : ['which', $binario];

        $process = new Process($comando);
        $process->run();

        return $process->isSuccessful();
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
