<?php

namespace App\ViewComposers\Navigation;

use App\Solicitor;
use App\ViewComposers\Composer;
use Illuminate\Contracts\View\View;

class PanelManagerComposer extends Composer
{
    public function compose(View $view): void
    {
        $view->with('onboarding', Solicitor::onboarding()->count());
    }
}
