<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressIdTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('transactions', 'address_id')) return;
        DB::statement("ALTER TABLE `transactions`
	    ADD COLUMN `address_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `reference`;");
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
