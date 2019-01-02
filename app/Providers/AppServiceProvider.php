<?php

namespace App\Providers;

use Faker\Generator;
use IAMProperty\Faker\Provider\Disbursement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('url')->forceRootUrl(config('app.url'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->afterResolving(Generator::class, function (Generator $faker) {
            $faker->addProvider(new Disbursement($faker));
        });
    }
}
