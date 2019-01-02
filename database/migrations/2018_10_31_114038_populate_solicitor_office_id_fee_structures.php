<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateSolicitorOfficeIdFeeStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            update legal_fees as fs
            inner join additional_fees as af on af.id = fs.fee_structure_id
            set fs.solicitor_office_id = af.solicitor_office_id
            where af.solicitor_office_id !=''");
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
