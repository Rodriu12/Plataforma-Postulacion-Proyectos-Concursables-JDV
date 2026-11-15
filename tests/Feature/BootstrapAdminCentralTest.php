<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('el comando crea un administrador central con la contraseña configurada', function () {
    config()->set('services.bootstrap_admin.email', 'adminvecindar@gmail.com');
    config()->set('services.bootstrap_admin.password', 'clave-local-de-prueba');

    expect(Artisan::call('app:bootstrap-admin-central'))->toBe(0);

    $user = User::where('email', 'adminvecindar@gmail.com')->firstOrFail();

    expect($user->role)->toBe(User::ROLE_ADMIN_CENTRAL)
        ->and($user->is_active)->toBeTrue()
        ->and(Hash::check('clave-local-de-prueba', $user->password))->toBeTrue();
});

test('el comando no modifica una cuenta existente', function () {
    config()->set('services.bootstrap_admin.email', 'adminvecindar@gmail.com');
    config()->set('services.bootstrap_admin.password', 'clave-local-de-prueba');

    $user = User::create([
        'name' => 'Cuenta existente',
        'email' => 'adminvecindar@gmail.com',
        'password' => 'otra-clave',
        'role' => 'vecino',
        'is_active' => true,
    ]);

    expect(Artisan::call('app:bootstrap-admin-central'))->toBe(0);

    $user->refresh();

    expect($user->role)->toBe('vecino')
        ->and(Hash::check('otra-clave', $user->password))->toBeTrue();
});

test('el comando falla si no hay contraseña de bootstrap configurada', function () {
    config()->set('services.bootstrap_admin.password', null);

    expect(Artisan::call('app:bootstrap-admin-central'))->toBe(1)
        ->and(User::where('email', 'adminvecindar@gmail.com')->exists())->toBeFalse();
});
