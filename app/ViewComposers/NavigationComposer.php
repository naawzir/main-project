<?php

namespace App\ViewComposers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\View;

class NavigationComposer extends Composer
{
    private $guard;

    public function __construct(Guard $auth)
    {
        $this->guard = $auth;
    }

    public function compose(View $view): void
    {
        $user = $this->guard->user();

        $view->with('nav', 'navigation.' . loadCorrectContent($user->id));
    }
}
