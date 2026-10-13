<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_returns_successfully(): void
    {
        $this->get('/')
            ->assertOk();
    }

    public function test_registration_url_redirects_to_filament_login(): void
    {
        $this->get('/register')
            ->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['logout-test-marker' => 'present'])
            ->get('/salir');

        $response->assertRedirect('/');
        $response->assertSessionMissing('logout-test-marker');
        $this->assertGuest();
    }

    public function test_approved_resident_can_download_their_certificate(): void
    {
        $user = User::factory()->create(['rut' => '12.345.678-9']);
        $resident = $user->vecino;
        $resident->update(['estado' => 'aprobado']);

        $this->actingAs($user)
            ->get(route('vecino.certificado', $resident))
            ->assertDownload('Certificado-Residencia-12.345.678-9.pdf');
    }

    public function test_another_resident_cannot_download_the_certificate(): void
    {
        $owner = User::factory()->create(['rut' => '12.345.678-9']);
        $resident = $owner->vecino;
        $resident->update(['estado' => 'aprobado']);
        $otherResident = User::factory()->create();

        $this->actingAs($otherResident)
            ->get(route('vecino.certificado', $resident))
            ->assertForbidden();
    }

    public function test_pending_resident_cannot_download_the_certificate(): void
    {
        $user = User::factory()->create(['rut' => '12.345.678-9']);

        $this->actingAs($user)
            ->get(route('vecino.certificado', $user->vecino))
            ->assertForbidden();
    }
}
