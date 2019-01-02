<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class BasePermissionsTcpUserRoles extends Migration
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
        Schema::table('user_roles', function ($table) {
            $table->string('base_permissions', 255);
        });
    }
}
