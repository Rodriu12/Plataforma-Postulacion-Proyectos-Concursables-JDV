<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProyectoExterno;
use App\Models\User;
use App\Mail\NovedadesProyectosMail;
use Illuminate\Support\Facades\Mail;

class EnviarNovedadesProyectos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:enviar-novedades-proyectos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía un correo diario con las novedades de proyectos a los usuarios a las 10:00 AM';

    public function handle()
    {
        $this->info('Buscando nuevos proyectos para el boletín...');

        $proyectos = ProyectoExterno::latest()->take(5)->get();

        if ($proyectos->isEmpty()) {
            $this->warn('No hay proyectos nuevos para enviar hoy.');
            return;
        }

        $usuarios = User::all();

        if ($usuarios->isEmpty()) {
            $this->warn('No hay usuarios registrados a quienes enviar el correo.');
            return;
        }

        foreach ($usuarios as $usuario) {
            Mail::to($usuario->email)->send(new NovedadesProyectosMail($proyectos));
            $this->line("Correo enviado a: {$usuario->email}");
        }

        $this->info('¡Boletín de novedades enviado exitosamente a todos los usuarios!');
    }
}
