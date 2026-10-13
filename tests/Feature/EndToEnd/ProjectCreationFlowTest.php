<?php

namespace Tests\Feature\EndToEnd;

use App\Filament\Resources\Proyectos\Pages\CreateProyecto;
use App\Models\Organizacion;
use App\Models\Proyecto;
use App\Models\User;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectCreationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_directive_member_logs_in_and_creates_a_project(): void
    {
        $organization = Organizacion::create(['nombre' => 'Junta Vecinal de Prueba']);
        $user = User::factory()->create([
            'organizacion_id' => $organization->id,
            'role' => 'presidente',
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

        Livewire::test(CreateProyecto::class)
            ->fillForm([
                'organizacion_id' => $organization->id,
                'titulo' => 'Mejoramiento de la sede',
                'estado' => 'borrador',
                'monto_solicitado' => 1500000,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('proyectos', [
            'organizacion_id' => $organization->id,
            'titulo' => 'Mejoramiento de la sede',
            'estado' => 'borrador',
            'monto_solicitado' => 1500000,
        ]);
        $this->assertSame(1, Proyecto::count());
    }
}
