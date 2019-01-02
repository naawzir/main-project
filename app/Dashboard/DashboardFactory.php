<?php declare(strict_types=1);

namespace App\Dashboard;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;

class DashboardFactory
{
    /**
     * @var App
     */
    private $app;

    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    public function create(string $roleSlug): Dashboard
    {
        if ($roleSlug === 'account-manager-lead') {
            return $this->app->make(AccountManager::class);
        }

        if ($roleSlug === 'bdm') {
            return $this->app->make(Director::class);
        }

        return $this->app->make('App\Dashboard\\' . studly_case($roleSlug));
    }
}
