<?php

namespace App\Providers;

use App\ReferenceGenerator\IncrementalReferenceDatabaseStorage;
use App\ReferenceGenerator\IncrementalReferenceStorage;
use App\ReferenceGenerator\ReferenceGenerator;
use App\ReferenceGenerator\IncrementalReferenceGenerator;
use Illuminate\Support\ServiceProvider;

class ReferenceGeneratorProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IncrementalReferenceStorage::class, function ($app) {
            return $app->make(IncrementalReferenceDatabaseStorage::class);
        });

        $this->app->singleton(ReferenceGenerator::class, function ($app) {
            return $app->make(IncrementalReferenceGenerator::class);
        });
    }
}
