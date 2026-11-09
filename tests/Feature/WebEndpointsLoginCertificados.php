<?php

use App\Models\User;

it('devuelve la página de inicio correctamente', function () {
    $this->withServerVariables(['HTTP_HOST' => 'localhost'])
        ->get('/')
        ->assertOk();
});

it('redirige el registro a la página de login de Filament', function () {
    $this->get('/register')->assertRedirect('/admin/login');
});

it('cierra la sesión de un usuario autenticado e invalida la sesión', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['logout-test-marker' => 'present'])
        ->get('/salir')
        ->assertRedirect('/')
        ->assertSessionMissing('logout-test-marker');

    $this->assertGuest();
});

it('permite a un residente aprobado descargar su certificado', function () {
    $user = User::factory()->create(['rut' => '12.345.678-9']);
    $resident = $user->vecino;
    $resident->update(['estado' => 'aprobado']);

    $this->actingAs($user)
        ->get(route('vecino.certificado', $resident))
        ->assertDownload('Certificado-Residencia-12.345.678-9.pdf');
});

it('deniega el acceso a otro residente al certificado', function () {
    $owner = User::factory()->create(['rut' => '12.345.678-9']);
    $resident = $owner->vecino;
    $resident->update(['estado' => 'aprobado']);
    $otherResident = User::factory()->create();

    $this->actingAs($otherResident)
        ->get(route('vecino.certificado', $resident))
        ->assertForbidden();
});

it('deniega la descarga del certificado mientras el residente está pendiente', function () {
    $user = User::factory()->create(['rut' => '12.345.678-9']);

    $this->actingAs($user)
        ->get(route('vecino.certificado', $user->vecino))
        ->assertForbidden();
});

it('redirige a los invitados fuera del panel de administración', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');
});

it('muestra la página de login del administrador para visitantes no autenticados', function () {
    $this->get('/admin/login')
        ->assertOk();
});
