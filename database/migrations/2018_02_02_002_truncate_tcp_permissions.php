<?php

use Illuminate\Database\Migrations\Migration;
use App\Permission;

class TruncateTcpPermissions extends Migration
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
