<?php

namespace Tests\Unit\ViewComposers\Navigation;

use App\Solicitor;
use App\SolicitorOffice;
use App\ViewComposers\Navigation\PanelManagerComposer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Unit\ViewComposers\TestCase;

class PanelManagerComposerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function Compose__AddsTheOnboardingVariable()
    {
        SolicitorOffice::getQuery()->delete();
        factory(SolicitorOffice::class, 3)
            ->state('onboarding:panel-manager')
            ->create([
                'solicitor_id' => function () {
                    return factory(Solicitor::class)->create()->id;
                }
            ]);
        $composer = new PanelManagerComposer();

        $this->view->expects($this->once())
            ->method('with')
            ->with('onboarding', 3);

        $composer->compose($this->view);
    }
}
