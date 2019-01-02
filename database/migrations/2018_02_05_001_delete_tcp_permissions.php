<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\UserRole;
use App\Permission;

class DeleteTcpPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up() // delete all records from tcp_permissions
    {
        Permission::truncate();
    }
}
