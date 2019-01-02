<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CaseDisbursementCorrections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (Schema::hasTable('case_disbursements')) return;
        Schema::table('case_disbursements', function (Blueprint $table) {
            $table->unsignedInteger('new_disbursement_id')->length(10)->after('case_id');
            $table->unsignedDecimal('cost', 13, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_disbursements', function (Blueprint $table) {
            $table->double('cost', 8, 2)->default(0.0)->change();
            $table->dropColumn('new_disbursement_id');
        });
    }
}
