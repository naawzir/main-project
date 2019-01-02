<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class HttpClientProvider extends ServiceProvider
{
    public $defer = true;

    public $bindings = [
        ClientInterface::class => Client::class
    ];

    /**
     * @return array
     */
    public function provides(): array
    {
        return [ClientInterface::class];
    }
}
