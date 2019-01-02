<?php

namespace App\Providers;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use Nessworthy\TextMarketer\Authentication;
use Nessworthy\TextMarketer\Endpoint\AccountEndpoint;
use Nessworthy\TextMarketer\Endpoint\CreditEndpoint;
use Nessworthy\TextMarketer\Endpoint\DeliveryReportEndpoint;
use Nessworthy\TextMarketer\Endpoint\GroupEndpoint;
use Nessworthy\TextMarketer\Endpoint\KeywordEndpoint;
use Nessworthy\TextMarketer\Endpoint\MessageEndpoint;
use Nessworthy\TextMarketer\TextMarketer;

class TextMarketerProvider extends ServiceProvider
{
    protected $defer = true;

    public $singletons = [
        AccountEndpoint::class => TextMarketer::class,
        CreditEndpoint::class => TextMarketer::class,
        DeliveryReportEndpoint::class => TextMarketer::class,
        GroupEndpoint::class => TextMarketer::class,
        KeywordEndpoint::class => TextMarketer::class,
        MessageEndpoint::class => TextMarketer::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            Authentication::class,
            function ($app) {
                return new Authentication\Simple(
                    config('textmarketer.username'),
                    config('textmarketer.password')
                );
            }
        );

        $this->app->singleton(
            TextMarketer::class,
            function ($app) {
                Textmarketer::ENDPOINT_SANDBOX;
                $environmentKey = 'ENDPOINT_' . config('textmarketer.environment');
                return new TextMarketer(
                    $app->make(Authentication::class),
                    \constant("Nessworthy\TextMarketer\TextMarketer::$environmentKey"),
                    $app->make(ClientInterface::class)
                );
            }
        );
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            AccountEndpoint::class,
            CreditEndpoint::class,
            DeliveryReportEndpoint::class,
            GroupEndpoint::class,
            KeywordEndpoint::class,
            MessageEndpoint::class,
            Authentication::class,
            TextMarketer::class,
        ];
    }
}
