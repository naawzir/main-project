<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUserIdCustomer extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('transaction_customers', 'customer_id')) return;
        Schema::table('transaction_customers', function ($table) {
            $table->renameColumn('user_id_customer', 'customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('transaction_customers', 'user_id_customer')) return;
        Schema::table('transaction_customers', function ($table) {
            $table->renameColumn('customer_id', 'user_id_customer');
        });
    }
}
