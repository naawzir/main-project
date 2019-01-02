<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;

abstract class Composer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    abstract public function compose(View $view): void;
}
