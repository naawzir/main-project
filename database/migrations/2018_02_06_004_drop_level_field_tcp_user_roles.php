<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DropLevelFieldTcpUserRoles extends Migration
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
        if (!Schema::hasColumn('user_roles', 'level')) return;
        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
}
