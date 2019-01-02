<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUserCustomerIdUserAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('user_addresses', 'customer_id')) return;

        Schema::table('user_addresses', function ($table) {

            $table->renameColumn('user_customer_id', 'customer_id');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('user_addresses', 'user_customer_id')) return;

        Schema::table('user_addresses', function ($table) {

            $table->renameColumn('customer_id', 'user_customer_id');

        });

    }

}
