<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeDateFieldNullableTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `transactions`
            CHANGE COLUMN `date_created` `date_created` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `service_collection_id`,
            CHANGE COLUMN `date_updated` `date_updated` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `date_created`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
