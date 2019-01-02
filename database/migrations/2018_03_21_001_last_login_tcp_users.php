<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class LastLoginTcpUsers extends Migration
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

        if (Schema::hasColumn('users', 'last_login')) return;

        $query = "ALTER TABLE `users` ADD COLUMN `last_login` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `email_marketing_opt_in`;";
        DB::statement($query);

    }

}
