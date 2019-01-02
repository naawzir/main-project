<?php

namespace Tests\Unit;

use App\Solicitor;
use App\SolicitorOffice;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SolicitorTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();
        SolicitorOffice::getQuery()->delete();
    }

    /** @test */
    public function generates_a_slug_when_created()
    {
        $solicitor = factory(Solicitor::class)->make([
            'slug' => null,
        ]);

        $solicitor->save();

        $this->assertNotNull($solicitor->slug);
    }

    /** @test */
    public function generates_a_slug_when_the_slug_is_empty()
    {
        $solicitor = factory(Solicitor::class)->make([
            'slug' => '',
        ]);

        $solicitor->save();

        $this->assertNotEquals('', $solicitor->slug);
    }

    /** @test */
    public function does_not_generate_a_slug_if_there_is_already_one()
    {
        $solicitor = factory(Solicitor::class)->make();
        $uuid = $solicitor->slug;

        $solicitor->save();

        $this->assertEquals($uuid, $solicitor->slug);
    }

    /** @test */
    public function Onboarding__AsBDM__FindsSolicitorWithOnboardingOffices()
    {
        $solicitor = factory(Solicitor::class)->create();
        factory(SolicitorOffice::class, 2)
            ->state('onboarding:bdm')
            ->create([
                'solicitor_id' => $solicitor->id,
            ]);

        $this->assertEquals(1, Solicitor::onboarding(true)->count());
    }

    /** @test */
    public function Onboarding__AsBDM__DoesNotFindSolicitorsThatAreWithThePanelManager()
    {
        factory(SolicitorOffice::class)
            ->state('onboarding:bdm')
            ->create([
                'solicitor_id' => function () {
                    return factory(Solicitor::class)->create()->id;
                }
            ]);
        factory(SolicitorOffice::class, 2)
            ->state('onboarding:panel-manager')
            ->create([
                'solicitor_id' => function () {
                    return factory(Solicitor::class)->create()->id;
                }
            ]);

        $this->assertEquals(1, Solicitor::onboarding(true)->count());
    }

    /** @test */
    public function Onboarding__NotAsBDM__FindsSolicitorWithOnboardingOffices()
    {
        $solicitor = factory(Solicitor::class)->create();
        factory(SolicitorOffice::class, 3)
            ->state('onboarding:panel-manager')
            ->create([
                'solicitor_id' => $solicitor->id,
            ]);

        $this->assertEquals(1, Solicitor::onboarding()->count());
    }

    /** @test */
    public function Onboarding__NotAsBDM__FindsSolicitorsWithOnboardingOffices()
    {
        factory(SolicitorOffice::class, 3)
            ->state('onboarding:panel-manager')
            ->create([
                'solicitor_id' => function () {
                    return factory(Solicitor::class)->create()->id;
                }
            ]);

        $this->assertEquals(3, Solicitor::onboarding()->count());
    }

    /** @test */
    public function Onboarding__NotAsBDM__DoesNotFindSolicitorsThatAreStillWithTheBDM()
    {
        factory(SolicitorOffice::class)
            ->state('onboarding:bdm')
            ->create([
                'solicitor_id' => function () {
                    return factory(Solicitor::class)->create()->id;
                }
            ]);
        factory(SolicitorOffice::class, 2)
            ->state('onboarding:panel-manager')
            ->create([
                'solicitor_id' => function () {
                    return factory(Solicitor::class)->create()->id;
                }
            ]);

        $this->assertEquals(2, Solicitor::onboarding()->count());
    }
}
