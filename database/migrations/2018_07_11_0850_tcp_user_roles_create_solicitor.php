<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TcpUserRolesCreateSolicitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = "INSERT INTO `user_roles` (`id`, `title`, `dashboard_title`, `description`, `base_permissions`, `date_created`, `date_updated`) VALUES ('11', 'Solicitor', 'solicitor', 'Solicitors', '3', '1531129500', '1531129500');";
        DB::statement($query);

    }

}
