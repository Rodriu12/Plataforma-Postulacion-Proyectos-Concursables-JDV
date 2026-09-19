<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\ProyectoExterno;
use Illuminate\Support\Facades\Log;
class SincronizarFondosGob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sincronizar-fondos-gob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extrae y actualiza los fondos concursables desde fondos.gob.cl';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Conectando a la portada de fondos.gob.cl...');

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ])->get('https://fondos.gob.cl/');

            if (!$response->successful()) {
                $this->error('No se pudo conectar a la página. Código: ' . $response->status());
                return;
            }

            $html = $response->body();
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);

            $elementos = $xpath->query('//a[contains(@href, "/ficha/")]');
            $contadorNuevos = 0;

            foreach ($elementos as $elemento) {
                if (!$elemento instanceof \DOMElement) {
                    continue;
                }

                $enlace = $elemento->getAttribute('href');
                if (empty($enlace)) {
                    continue;
                }

                $cardText = $elemento->textContent;
                $lines = array_values(array_filter(array_map('trim', explode("\n", $cardText))));

                $estadoVigencia = 'abierto'; // por defecto
                $cardTextUpper = mb_strtoupper($cardText);
                
                if (str_contains($cardTextUpper, 'POR ABRIR') || str_contains($cardTextUpper, 'PRÓXIMAMENTE') || str_contains($cardTextUpper, 'PROXIMAMENTE')) {
                    $estadoVigencia = 'por_abrir';
                } elseif (str_contains($cardTextUpper, 'CERRADO')) {
                    $estadoVigencia = 'cerrado';
                }

                $institucion = 'Estado de Chile';
                foreach ($lines as $line) {
                    $lineUpper = mb_strtoupper($line);
                    if (
                        str_contains($lineUpper, 'GOBIERNO REGIONAL') || 
                        str_contains($lineUpper, 'MINISTERIO') || 
                        str_contains($lineUpper, 'CORFO') || 
                        str_contains($lineUpper, 'SERCOTEC') || 
                        str_contains($lineUpper, 'FOSIS') || 
                        str_contains($lineUpper, 'SUBSECRETARÍA') ||
                        str_contains($lineUpper, 'INDAP') ||
                        str_contains($lineUpper, 'SEREMI') ||
                        str_contains($lineUpper, 'INSTITUTO NACIONAL')
                    ) {
                        if (strlen($line) > 3 && strlen($line) < 100) {
                            $institucion = $line;
                            break;
                        }
                    }
                }

                $titulo = '';
                foreach ($lines as $line) {
                    $lineUpper = mb_strtoupper($line);
                    
                    if (
                        in_array($lineUpper, ['ABIERTO', 'CERRADO', 'POR ABRIR', 'PRÓXIMAMENTE', 'NACIONAL', 'REGIONAL', 'INTERNACIONAL']) || 
                        str_starts_with($lineUpper, 'FIN:') || 
                        str_starts_with($lineUpper, 'INICIO:') ||
                        str_contains($lineUpper, 'VER MÁS') ||
                        $line === $institucion
                    ) {
                        continue;
                    }

                    if (strlen($line) > 15 && $line !== $institucion) {
                        $titulo = $line;
                        break;
                    }
                }

                if (empty($titulo)) {
                    $titulo = $lines[1] ?? 'Fondo Concursable del Estado';
                }

                // 4. Extraer Fecha de Cierre
                $fechaCierre = null;
                if (preg_match('/(?:Fin|Cierre):\s*([0-9]{2}-[0-9]{2}-[0-9]{4})/i', $cardText, $matchFecha)) {
                    $partes = explode('-', $matchFecha[1]);
                    if (count($partes) === 3) {
                        $fechaCierre = "{$partes[2]}-{$partes[1]}-{$partes[0]}";
                    }
                }

                if (!str_starts_with($enlace, 'http')) {
                    $enlace = 'https://fondos.gob.cl' . (str_starts_with($enlace, '/') ? '' : '/') . $enlace;
                }

                ProyectoExterno::updateOrCreate(
                    ['url_fuente' => $enlace],
                    [
                        'titulo' => $titulo,
                        'descripcion' => trim($cardText),
                        'institucion' => $institucion,
                        'fecha_cierre' => $fechaCierre,
                        'estado_vigencia' => $estadoVigencia,
                    ]
                );

                $contadorNuevos++;
            }

            $this->info("¡Sincronización exitosa! Se procesaron {$contadorNuevos} registros detectando sus estados.");

        } catch (\Exception $e) {
            $this->error('Error durante el scraping: ' . $e->getMessage());
            Log::error('Error en SincronizarFondosGob: ' . $e->getMessage());
        }
    }
}
