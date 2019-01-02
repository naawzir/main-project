<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class UserIdCreatedTcpCases extends Migration
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

        if (Schema::hasColumn('cases', 'user_id_created')) return;

        $query = "ALTER TABLE `cases` ADD COLUMN `user_id_created` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `solicitor_user_id`;";
        DB::statement($query);

    }

}
