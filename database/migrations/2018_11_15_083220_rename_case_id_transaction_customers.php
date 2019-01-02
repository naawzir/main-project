<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCaseIdTransactionCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('transaction_customers', 'transaction_id')) return;
        Schema::table('transaction_customers', function (Blueprint $table) {
            $table->renameColumn('case_id', 'transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('transaction_customers', 'case_id')) return;
        Schema::table('transaction_customers', function (Blueprint $table) {
            $table->renameColumn('transaction_id', 'case_id');
        });
    }
}
