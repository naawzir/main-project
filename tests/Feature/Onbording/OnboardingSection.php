<?php

namespace Tests\Feature\Onbording;

use App\Solicitor;
use App\SolicitorOffice;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\TestCase;

class OnboardingSection extends TestCase
{
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();
        SolicitorOffice::getQuery()->delete();
    }

    /** @test */
    public function a_bdm_can_get_a_count_of_how_many_solicitors_are_onboarding()
    {
        $user = factory(User::class)->state('role:bdm')->create();
        $solicitor = factory(Solicitor::class)->create();
        factory(SolicitorOffice::class, 3)->state('onboarding:bdm')->create([
            'solicitor_id' => $solicitor->id,
        ]);

        $this->actingAs($user)
            ->get('/solicitors/onboarding/get-onboarding')
            ->assertResponseOk()
            ->seeJsonContains(['count' => 1]);
    }

    /** @test */
    public function a_panel_manager_can_get_a_count_of_how_many_solicitors_are_onboarding()
    {
        $user = factory(User::class)->state('role:panel-manager')->create();
        factory(SolicitorOffice::class, 3)->state('onboarding:panel-manager')->create([
            'solicitor_id' => function () {
                return factory(Solicitor::class)->create()->id;
            },
        ]);

        $this->actingAs($user)
            ->get('/solicitors/onboarding/get-onboarding')
            ->assertResponseOk()
            ->seeJsonContains(['count' => 3]);
    }
}
