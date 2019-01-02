<?php

namespace App\Providers;

use Academe\IdealPostcodes\Transport;
use Academe\IdealPostcodes\TransportInterface;
use App\PostcodeLookup\CacheWrapperPostcodeLookup;
use App\PostcodeLookup\IdealPostcodeLookup;
use App\PostcodeLookup\PostcodeLookup;
use App\PostcodeLookup\PostcodeLookupCache;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class PostcodeLookupProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PostcodeLookup::class, function (Container $app) {
            return $app->make(
                CacheWrapperPostcodeLookup::class,
                [
                    'postcodeLookup' => $app->make(IdealPostcodeLookup::class),
                    'cacheRepository' => $app->make(Factory::class)->store(config('idealpostcodes.store')),
                    'cacheExpires' => config('idealpostcodes.cache_expires')
                ]
            );
        });

        $this->app->singleton(TransportInterface::class, function (Container $app) {
            $app
                ->when(Transport::class)
                ->needs('$api_key')
                ->give(config('idealpostcodes.key'));

            return $app->make(Transport::class);
        });

        $this->app->singleton(PostcodeLookupCache::class, CacheWrapperPostcodeLookup::class);
    }

    public function provides()
    {
        return [
            PostcodeLookup::class,
            TransportInterface::class,
            PostcodeLookupCache::class
        ];
    }
}
