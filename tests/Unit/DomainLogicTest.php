<?php

use App\Models\Proyecto;
use App\Models\User;
use App\Policies\ProyectoPolicy;

it('normalizes user roles', function () {
    $user = new User(['role' => '  PRESIDENTE  ']);

    expect($user->role)->toBe('presidente');
});

it('identifies a central administrator by role', function () {
    $admin = new User(['role' => User::ROLE_ADMIN_CENTRAL]);
    $resident = new User(['role' => 'vecino']);

    expect($admin->esAdminCentral())->toBeTrue()
        ->and($resident->esAdminCentral())->toBeFalse();
});

it('normalizes project states', function () {
    $project = new Proyecto(['estado' => '  EN_POSTULACION  ']);

    expect($project->estado)->toBe('en_postulacion');
});

it('only lets directive members update projects in their organization', function () {
    $policy = new ProyectoPolicy;
    $user = (new User)->forceFill([
        'role' => 'presidente',
        'organizacion_id' => 10,
    ]);
    $ownProject = (new Proyecto)->forceFill(['organizacion_id' => 10]);
    $otherProject = (new Proyecto)->forceFill(['organizacion_id' => 20]);

    expect($policy->update($user, $ownProject))->toBeTrue()
        ->and($policy->update($user, $otherProject))->toBeFalse();
});

it('blocks panel access when a user is inactive', function () {
    $user = new User([
        'role' => 'vecino',
        'organizacion_id' => null,
        'is_active' => false,
    ]);

    expect($user->canAccessPanel(new \Filament\Panel))->toBeFalse();
});

it('allows the central admin to access the panel without an organization', function () {
    $user = new User([
        'role' => User::ROLE_ADMIN_CENTRAL,
        'organizacion_id' => null,
        'is_active' => true,
    ]);

    expect($user->canAccessPanel(new \Filament\Panel))->toBeTrue();
});
