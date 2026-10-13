<?php

namespace Tests\Unit;

use App\Models\Proyecto;
use App\Models\User;
use App\Policies\ProyectoPolicy;
use PHPUnit\Framework\TestCase;

class DomainLogicTest extends TestCase
{
    public function test_user_role_is_normalized(): void
    {
        $user = new User(['role' => '  PRESIDENTE  ']);

        $this->assertSame('presidente', $user->role);
    }

    public function test_user_identifies_central_administrator(): void
    {
        $admin = new User(['role' => User::ROLE_ADMIN_CENTRAL]);
        $resident = new User(['role' => 'vecino']);

        $this->assertTrue($admin->esAdminCentral());
        $this->assertFalse($resident->esAdminCentral());
    }

    public function test_project_state_is_normalized(): void
    {
        $project = new Proyecto(['estado' => '  EN_POSTULACION  ']);

        $this->assertSame('en_postulacion', $project->estado);
    }

    public function test_project_policy_limits_updates_to_the_user_organization(): void
    {
        $policy = new ProyectoPolicy;
        $user = (new User)->forceFill([
            'role' => 'presidente',
            'organizacion_id' => 10,
        ]);
        $ownProject = (new Proyecto)->forceFill(['organizacion_id' => 10]);
        $otherProject = (new Proyecto)->forceFill(['organizacion_id' => 20]);

        $this->assertTrue($policy->update($user, $ownProject));
        $this->assertFalse($policy->update($user, $otherProject));
    }
}
