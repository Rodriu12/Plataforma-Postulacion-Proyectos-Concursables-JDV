<?php

namespace App\Jobs;

use App\Mail\NovedadesProyectosMail;
use App\Models\ProyectoExterno;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarNovedadesProyectosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $proyectos = ProyectoExterno::latest()->take(5)->get();

        if ($proyectos->isEmpty()) {
            return;
        }

        $usuarios = User::all();

        foreach ($usuarios as $usuario) {
            if (filled($usuario->email)) {
                Mail::to($usuario->email)->send(new NovedadesProyectosMail($proyectos));
            }
        }
    }
}
