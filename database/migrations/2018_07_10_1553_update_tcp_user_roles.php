<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTcpUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('user_roles', 'dashboard_title')) return;

        $query = "ALTER TABLE `user_roles`
	      ADD COLUMN `dashboard_title` VARCHAR(64) NOT NULL DEFAULT '' AFTER `title`";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='superuser' WHERE  `id`=1;";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='director' WHERE  `id`=2;";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='bdm' WHERE  `id`=3;";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='panel-manager' WHERE  `id`=4;";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='account-manager-lead' WHERE  `id`=5;";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='account-manager' WHERE  `id`=6;";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='business-owner' WHERE  `id`=7;";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='branch-manager' WHERE  `id`=8;";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='agent' WHERE  `id`=9;";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `dashboard_title`='customer' WHERE  `id`=10;";
        DB::statement($query);

    }

}
