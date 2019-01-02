<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\UserRole;
use App\Permission;

class NavigationSubUrlTcpPermissions extends Migration
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
        Schema::table('permissions', function ($table) {
            $table->unsignedInteger('navigation')->default('0');
            $table->unsignedInteger('sub_url')->default('0');
        });
    }
}
