<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BootstrapAdminCentral extends Command
{
    protected $signature = 'app:bootstrap-admin-central';

    protected $description = 'Crea la cuenta inicial de administrador central si todavía no existe';

    public function handle(): int
    {
        $email = config('services.bootstrap_admin.email');
        $password = config('services.bootstrap_admin.password');

        if (blank($password)) {
            $this->error('Configura BOOTSTRAP_ADMIN_PASSWORD en el archivo .env local.');

            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->components->warn("La cuenta {$email} ya existe; no se modificaron sus credenciales ni su rol.");

            return self::SUCCESS;
        }

        User::create([
            'name' => 'Administrador Central',
            'email' => $email,
            'password' => $password,
            'role' => User::ROLE_ADMIN_CENTRAL,
            'is_active' => true,
        ]);

        $this->newLine();
        $this->info('Configuración inicial completada.');
        $this->line("Usuario: {$email}");
        $this->line('Contraseña: consulta el valor local de BOOTSTRAP_ADMIN_PASSWORD en .env.');
        $this->warn('Cambia la contraseña después del primer inicio de sesión.');

        return self::SUCCESS;
    }
}
