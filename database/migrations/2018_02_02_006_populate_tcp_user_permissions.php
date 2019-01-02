<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\UserRole;
use App\Permission;

class PopulateTcpUserPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up()
    {
        $user = new User;
        $users =
            $user
                ->whereNotNull('user_role_id')
                ->whereNotIn('user_role_id', [0])
                ->where('active', 1) // for now, I'll only do this for active users but in reality should be done for all users
                ->get();


        foreach($users as $user) {

            $basePermissions = UserRole::find($user->user_role_id)->base_permissions;
            $permission = new Permission;
            $permission->getPermissions($basePermissions, $user->id);

        }

    }
}
