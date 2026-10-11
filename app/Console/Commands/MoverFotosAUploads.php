<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MoverFotosAUploads extends Command
{
    protected $signature = 'app:mover-fotos-a-uploads';

    protected $description = 'Copia las fotos ya subidas (avatares y fotos de proyectos) a public/uploads, el disco "uploads" que no depende de storage:link';

    public function handle(): int
    {
        $origenes = [
            'avatares' => [
                storage_path('app/public/avatares'),
            ],
            'proyectos-terreno' => [
                storage_path('app/private/proyectos-terreno'),
                storage_path('app/public/proyectos-terreno'),
            ],
        ];

        $copiados = 0;
        $yaExistian = 0;

        foreach ($origenes as $carpetaDestino => $rutasOrigen) {
            $destinoBase = public_path("uploads/{$carpetaDestino}");

            foreach ($rutasOrigen as $origen) {
                if (! File::isDirectory($origen)) {
                    continue;
                }

                File::ensureDirectoryExists($destinoBase);

                foreach (File::files($origen) as $archivo) {
                    $destino = $destinoBase . DIRECTORY_SEPARATOR . $archivo->getFilename();

                    if (File::exists($destino)) {
                        $yaExistian++;

                        continue;
                    }

                    File::copy($archivo->getPathname(), $destino);
                    $copiados++;
                    $this->line("  copiado: {$carpetaDestino}/{$archivo->getFilename()}");
                }
            }
        }

        $this->info("Listo: {$copiados} archivo(s) copiados a public/uploads ({$yaExistian} ya estaban). Los originales no se borraron.");

        return self::SUCCESS;
    }
}
