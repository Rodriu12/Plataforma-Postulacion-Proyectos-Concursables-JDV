<?php

namespace Tests\Feature\EndToEnd;

use App\Filament\Resources\Emergencias\Pages\CreateEmergencia;
use App\Models\Emergencia;
use App\Models\Organizacion;
use App\Models\User;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EmergencyReportingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_logs_in_and_reports_an_emergency(): void
    {
        $organization = Organizacion::create(['nombre' => 'Junta Vecinal de Prueba']);
        $user = User::factory()->create([
            'organizacion_id' => $organization->id,
            'role' => 'vecino',
            'rut' => '11.222.333-4',
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $user->email,
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);
        $this->get('/admin')->assertOk();

        Livewire::test(CreateEmergencia::class)
            ->fillForm([
                'vecino_id' => $user->vecino->id,
                'organizacion_id' => $organization->id,
                'tipo' => 'corte_servicio',
                'ubicacion' => 'Pasaje Los Aromos 123',
                'descripcion' => 'Corte de agua en el sector.',
                'estado' => 'pendiente',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('emergencias', [
            'vecino_id' => $user->vecino->id,
            'organizacion_id' => $organization->id,
            'tipo' => 'corte_servicio',
            'ubicacion' => 'Pasaje Los Aromos 123',
            'estado' => 'pendiente',
        ]);
        $this->assertSame(1, Emergencia::count());
    }
}
