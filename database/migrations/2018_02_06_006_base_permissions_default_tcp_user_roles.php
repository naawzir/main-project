<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class BasePermissionsDefaultTcpUserRoles extends Migration
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
        Schema::table('user_roles', function (Blueprint $table) {
            $table->string('base_permissions', 255)->nullable()->change();
        });
    }
}
