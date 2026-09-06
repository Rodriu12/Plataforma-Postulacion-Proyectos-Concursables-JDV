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

            // Buscamos directamente todos los enlaces que llevan a fichas (son DOMElement seguros)
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

                // Buscamos si hay un título h3 o h4 dentro de este enlace
                $titulosNodes = $xpath->query('.//h3 | .//h4', $elemento);
                $titulo = '';

                if ($titulosNodes->length > 0 && $titulosNodes->item(0) instanceof \DOMElement) {
                    $titulo = trim($titulosNodes->item(0)->textContent);
                } else {
                    // Si no tiene h3/h4 interno, tomamos la primera línea limpia del texto del enlace
                    $textoLimpio = trim($elemento->textContent);
                    $lineas = explode("\n", $textoLimpio);
                    $titulo = trim($lineas[0]);
                }

                // Validamos que el título tenga una longitud lógica
                if (strlen($titulo) < 5 || strlen($titulo) > 250) {
                    continue;
                }

                $tituloLower = strtolower($titulo);
                if (str_contains($tituloLower, 'ver más') || str_contains($tituloLower, 'iniciar sesión')) {
                    continue;
                }

                if (!str_starts_with($enlace, 'http')) {
                    $enlace = 'https://fondos.gob.cl' . (str_starts_with($enlace, '/') ? '' : '/') . $enlace;
                }

                // Guardamos o actualizamos en la base de datos manteniendo el histórico
                ProyectoExterno::updateOrCreate(
                    ['url_fuente' => $enlace],
                    [
                        'titulo' => $titulo,
                        'descripcion' => 'Extraído automáticamente desde la portada de fondos.gob.cl',
                        'institucion' => 'Estado de Chile',
                        'estado_vigencia' => 'abierto',
                    ]
                );

                $contadorNuevos++;
            }

            $this->info("¡Sincronización exitosa! Se procesaron y guardaron {$contadorNuevos} fondos.");

        } catch (\Exception $e) {
            $this->error('Error durante el scraping: ' . $e->getMessage());
            Log::error('Error en SincronizarFondosGob: ' . $e->getMessage());
        }
    }
}
