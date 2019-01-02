<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTcpCaseUserCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('transaction_customers') && Schema::hasTable('tcp_case_user_customers')) {

            Schema::rename('tcp_case_user_customers', 'transaction_customers');

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('tcp_case_user_customers') && Schema::hasTable('transaction_customers')) {

            Schema::rename('transaction_customers', 'tcp_case_user_customers');

        }

    }

}