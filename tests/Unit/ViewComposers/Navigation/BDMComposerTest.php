<?php

namespace Tests\Unit\ViewComposers\Navigation;

use App\Solicitor;
use App\SolicitorOffice;
use App\ViewComposers\Navigation\BDMComposer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Unit\ViewComposers\TestCase;

class BDMComposerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function Compose__AddsTheOnboardingVariable()
    {
        SolicitorOffice::getQuery()->delete();
        factory(SolicitorOffice::class)
            ->state('onboarding:bdm')
            ->create([
                'solicitor_id' => function () {
                    return factory(Solicitor::class)->create()->id;
                },
            ]);

        $composer = new BDMComposer();

        $this->view->expects($this->once())
            ->method('with')
            ->with('onboarding', 1);

        $composer->compose($this->view);
    }
}
