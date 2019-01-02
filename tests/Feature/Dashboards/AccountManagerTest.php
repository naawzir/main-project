<?php

namespace Tests\Feature\Dashboards;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\TestCase;

class AccountManagerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->state('role:account-manager')->create();
    }

    /** @test */
    public function can_access_dashboard()
    {
        $this->actingAs($this->user)
            ->get('/')
            ->assertResponseStatus(200);
    }

    /** @test */
    public function can_access_branch_performance_page()
    {
        $this->actingAs($this->user)
            ->get('/branch/')
            ->assertResponseStatus(200);
    }

    /** @test */
    public function clicking_mtdInstructions_goes_to_cases()
    {
        $this->markTestIncomplete('Waiting for a refactor of the blade template.');
        $this->actingAs($this->user)
            ->visit('/')
            ->click('mtdInstructions')
            ->seePageIs('/cases');
    }

    /** @test */
    public function clicking_dailyInstructions_goes_to_cases()
    {
        $this->markTestIncomplete('Waiting for a refactor of the blade template.');
        $this->actingAs($this->user)
            ->visit('/')
            ->click('dailyInstructions')
            ->seePageIs('/cases');
    }

    /** @test */
    public function clicking_new_leads_goes_to_cases()
    {
        $this->markTestIncomplete('Waiting for a refactor of the blade template.');
        $this->actingAs($this->user)
            ->visit('/')
            ->click('new-leads')
            ->seePageIs('/cases');
    }

    /** @test */
    public function clicking_unpanelled_goes_to_cases()
    {
        $this->markTestIncomplete('Waiting for a refactor of the blade template.');
        $this->actingAs($this->user)
            ->visit('/')
            ->click('unpanelled')
            ->seePageIs('/cases');
    }

    /** @test */
    public function clicking_completions_goes_to_cases()
    {
        $this->markTestIncomplete('Waiting for a refactor of the blade template.');
        $this->actingAs($this->user)
            ->visit('/')
            ->click('completions')
            ->seePageIs('/cases');
    }
}
