<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUserIdCustomerCaseDisbursements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('case_disbursements', 'customer_id')) return;

        Schema::table('case_disbursements', function ($table) {

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
        if (Schema::hasColumn('case_disbursements', 'user_id_customer')) return;

        Schema::table('case_disbursements', function ($table) {

            $table->renameColumn('customer_id', 'user_id_customer');

        });
    }
}
