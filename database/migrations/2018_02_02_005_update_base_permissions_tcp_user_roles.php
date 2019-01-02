<?php

use Illuminate\Database\Migrations\Migration;

class UpdateBasePermissionsTcpUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up() // update records to tcp_user_roles
    {
        $query = "UPDATE `user_roles` SET `base_permissions`='1,4,5,22,23,24,25,26,27,28,29,30,31,32,33' WHERE `id`=2";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `base_permissions`='1,4,5,22,23,24,25,26,27,28,29,30,31,32,33' WHERE `id`=3";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `base_permissions`='1,4,5,22,23,24,25,26,27,28,29,30,31,32,33' WHERE `id`=4";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `base_permissions`='1,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33' WHERE `id`=5";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `base_permissions`='1,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33' WHERE `id`=6";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `base_permissions`='2,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,30,31,32,33' WHERE `id`=7";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `base_permissions`='2,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,30,31,32,33' WHERE `id`=8";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `base_permissions`='2,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,30,31,32,33' WHERE `id`=9";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `base_permissions`='3' WHERE `id`=10";
        DB::statement($query);

    }

}
