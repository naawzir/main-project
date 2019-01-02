<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AmendmentsToFeeStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('legal_fees', 'solicitor_office_id')) {
            DB::statement("ALTER TABLE `legal_fees` ADD COLUMN `solicitor_office_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `fee_structure_id`;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('legal_fees', 'solicitor_office_id'))
        {
            Schema::table('legal_fees', function (Blueprint $table)
            {
                $table->dropColumn('solicitor_office_id');
            });
        }
    }
}
