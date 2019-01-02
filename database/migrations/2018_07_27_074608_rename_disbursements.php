<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDisbursements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('solicitor_fees') && Schema::hasTable('disbursements')) {

            Schema::rename('disbursements', 'solicitor_fees');

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('disbursements') && Schema::hasTable('solicitor_fees')) {

            Schema::rename('solicitor_fees', 'disbursements');

        }

    }

}