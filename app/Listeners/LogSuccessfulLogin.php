<?php

namespace App\Listeners;

use App\UserPermission;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $userPermissionModel = new UserPermission;
        $getUserPermissions = $userPermissionModel->getUserPermissions();
        session(['user_permissions' => $getUserPermissions]);

        $event->user->last_login = time();
        $event->user->save();
    }
}
