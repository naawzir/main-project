<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class UserRoleIdUsersTable extends Migration
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
        Schema::table('users', function ($table) {
            $table->integer('user_role_id')->nullable()->change();
        });
    }
}
