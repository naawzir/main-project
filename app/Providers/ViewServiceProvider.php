<?php

namespace App\Providers;

use App\ViewComposers\Navigation\BDMComposer;
use App\ViewComposers\Navigation\PanelManagerComposer;
use App\ViewComposers\NavigationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    private $composers = [
        NavigationComposer::class => 'navigation',
        BDMComposer::class => 'navigation.bdm',
        PanelManagerComposer::class => 'navigation.panel-manager',
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->composers as $composer => $views) {
            View::composer($views, $composer);
        }
    }
}
