<?php

use App\Models\User;

it('returns the home page successfully', function () {
    $this->withServerVariables(['HTTP_HOST' => 'localhost'])
        ->get('/')
        ->assertOk();
});

it('redirects registration to the Filament login page', function () {
    $this->get('/register')->assertRedirect('/admin/login');
});

it('logs out an authenticated user and invalidates the session', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['logout-test-marker' => 'present'])
        ->get('/salir')
        ->assertRedirect('/')
        ->assertSessionMissing('logout-test-marker');

    $this->assertGuest();
});

it('lets an approved resident download their certificate', function () {
    $user = User::factory()->create(['rut' => '12.345.678-9']);
    $resident = $user->vecino;
    $resident->update(['estado' => 'aprobado']);

    $this->actingAs($user)
        ->get(route('vecino.certificado', $resident))
        ->assertDownload('Certificado-Residencia-12.345.678-9.pdf');
});

it('denies another resident access to the certificate', function () {
    $owner = User::factory()->create(['rut' => '12.345.678-9']);
    $resident = $owner->vecino;
    $resident->update(['estado' => 'aprobado']);
    $otherResident = User::factory()->create();

    $this->actingAs($otherResident)
        ->get(route('vecino.certificado', $resident))
        ->assertForbidden();
});

it('denies certificate downloads while the resident is pending', function () {
    $user = User::factory()->create(['rut' => '12.345.678-9']);

    $this->actingAs($user)
        ->get(route('vecino.certificado', $user->vecino))
        ->assertForbidden();
});
