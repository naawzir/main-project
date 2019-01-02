<?php
namespace Tests\Feature;

use App\Agency;
use App\AgencyBranch;
use App\AgentUser;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Auth;

class UserLoginsDashboardTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @dataProvider roleProvider
     */
    public function can_access_dashboard($role)
    {
        $user = factory(User::class)->state('role:' . $role)->create();

        $this->actingAs($user)
            ->get('')
            ->assertViewIs('dashboards.' . $role);
    }

    /** @test */
    public function business_owner_can_access_dashboard()
    {
        $agency = factory(Agency::class)->create();
        $branch = factory(AgencyBranch::class)->create([
            'agency_id' => $agency->id,
        ]);
        $user = factory(User::class)->state('role:business-owner')->create();
        factory(AgentUser::class)->create([
            'agency_id' => $agency->id,
            'agency_branch_id' => $branch->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get('')
            ->assertViewIs('dashboards.business-owner');
    }

    public function roleProvider()
    {
        return [
            ['director'],
            ['panel-manager'],
            ['account-manager-lead'],
            ['agent'],
        ];
    }
}








