<?php

use Illuminate\Database\Migrations\Migration;
use App\UserPermission;

class TruncateTcpUserPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up() // delete all records from tcp_user_permissions
    {
       UserPermission::truncate();
    }
}
