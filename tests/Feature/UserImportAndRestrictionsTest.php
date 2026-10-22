<?php

use App\Filament\Imports\ResidenteImporter;
use App\Mail\RestablecerContrasenaMail;
use App\Models\Organizacion;
use App\Models\User;
use App\Models\Vecino;
use App\Models\Voluntario;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('un usuario inactivo no puede acceder al panel', function () {
    $panel = new Panel;
    $panel->id('admin');

    $org = Organizacion::create([
        'nombre' => 'Junta Los Copihues',
        'rut_juridico' => '65.123.456-7',
        'direccion' => 'Calle 1 #123',
    ]);

    $user = User::create([
        'name' => 'Juan Pérez',
        'email' => 'juan@ejemplo.com',
        'password' => bcrypt('password123'),
        'role' => 'vecino',
        'organizacion_id' => $org->id,
        'is_active' => false,
    ]);

    expect($user->canAccessPanel($panel))->toBeFalse();
});

test('un usuario sin organizacion asignada que no sea admin central no puede acceder al panel', function () {
    $panel = new Panel;
    $panel->id('admin');

    $user = User::create([
        'name' => 'Vecino Sin Junta',
        'email' => 'sinjunta@ejemplo.com',
        'password' => bcrypt('password123'),
        'role' => 'vecino',
        'organizacion_id' => null,
        'is_active' => true,
    ]);

    expect($user->canAccessPanel($panel))->toBeFalse();
});

test('el admin central puede acceder al panel incluso sin organizacion asignada', function () {
    $panel = new Panel;
    $panel->id('admin');

    $admin = User::create([
        'name' => 'Super Admin',
        'email' => 'admin@ejemplo.com',
        'password' => bcrypt('password123'),
        'role' => User::ROLE_ADMIN_CENTRAL,
        'organizacion_id' => null,
        'is_active' => true,
    ]);

    expect($admin->canAccessPanel($panel))->toBeTrue();
});

test('al asignarle una organizacion a un usuario inactivo se activa automaticamente y envia correo de restablecimiento', function () {
    Mail::fake();

    $user = User::create([
        'name' => 'Persona Importada',
        'email' => 'importado@ejemplo.com',
        'password' => bcrypt('password123'),
        'role' => 'vecino',
        'organizacion_id' => null,
        'is_active' => false,
    ]);

    expect($user->is_active)->toBeFalse();

    $org = Organizacion::create([
        'nombre' => 'Junta Esperanza',
        'rut_juridico' => '70.123.456-8',
        'direccion' => 'Av Principal 456',
    ]);

    $user->update([
        'organizacion_id' => $org->id,
    ]);

    $user->refresh();

    expect($user->organizacion_id)->toBe($org->id)
        ->and($user->is_active)->toBeTrue();

    Mail::assertSent(RestablecerContrasenaMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('las reglas de ResidenteImporter validan nombres en espanol sin numeros ni simbolos', function () {
    $columns = collect(ResidenteImporter::getColumns())->keyBy(fn ($c) => $c->getName());
    $nameColumn = $columns->get('name');

    expect($nameColumn)->not->toBeNull();

    $rules = $nameColumn->getDataValidationRules();

    $validatorValido = validator(['name' => 'María José Ñandú Peña-López'], ['name' => $rules]);
    expect($validatorValido->passes())->toBeTrue();

    $validatorConNumeros = validator(['name' => 'Juan123'], ['name' => $rules]);
    expect($validatorConNumeros->fails())->toBeTrue();

    $validatorConSimbolos = validator(['name' => 'Pedro #$@!'], ['name' => $rules]);
    expect($validatorConSimbolos->fails())->toBeTrue();
});

test('las reglas de ResidenteImporter validan el formato de RUT', function () {
    $columns = collect(ResidenteImporter::getColumns())->keyBy(fn ($c) => $c->getName());
    $rutColumn = $columns->get('rut');

    $rules = $rutColumn->getDataValidationRules();

    $validoConPuntos = validator(['rut' => '12.345.678-9'], ['rut' => $rules]);
    expect($validoConPuntos->passes())->toBeTrue();

    $validoSinPuntos = validator(['rut' => '12345678-K'], ['rut' => $rules]);
    expect($validoSinPuntos->passes())->toBeTrue();

    $invalido = validator(['rut' => '1234-invalid'], ['rut' => $rules]);
    expect($invalido->fails())->toBeTrue();
});

test('las reglas de ResidenteImporter validan telefono y rol', function () {
    $columns = collect(ResidenteImporter::getColumns())->keyBy(fn ($c) => $c->getName());

    $phoneRules = $columns->get('phone')->getDataValidationRules();
    expect(validator(['phone' => '+56 9 1234 5678'], ['phone' => $phoneRules])->passes())->toBeTrue();
    expect(validator(['phone' => '12345678abc'], ['phone' => $phoneRules])->fails())->toBeTrue();

    $roleRules = $columns->get('role')->getDataValidationRules();
    expect(validator(['role' => 'vecino'], ['role' => $roleRules])->passes())->toBeTrue();
    expect(validator(['role' => 'voluntario'], ['role' => $roleRules])->passes())->toBeTrue();
    expect(validator(['role' => 'admin_hacker'], ['role' => $roleRules])->fails())->toBeTrue();
});
