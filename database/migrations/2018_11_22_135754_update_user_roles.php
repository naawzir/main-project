<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `user_roles` SET `group`='Staff' WHERE `id`=4");
        DB::statement("UPDATE `user_roles` SET `group`='Staff' WHERE `id`=3");
        DB::statement("UPDATE `user_roles` SET `group`='Staff' WHERE `id`=2");
        DB::statement("UPDATE `user_roles` SET `group`='Staff' WHERE `id`=1");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE `user_roles` SET `group`='' WHERE `id`=4");
        DB::statement("UPDATE `user_roles` SET `group`='' WHERE `id`=3");
        DB::statement("UPDATE `user_roles` SET `group`='' WHERE `id`=2");
        DB::statement("UPDATE `user_roles` SET `group`='' WHERE `id`=1");
    }
}
