<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCaseTypesPricingStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("update legal_fees set case_type = 'sale' where case_type = 'Sale';");
        DB::statement("update legal_fees set case_type = 'purchase' where case_type = 'Purchase';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("update legal_fees set case_type = 'Sale' where case_type = 'sale';");
        DB::statement("update legal_fees set case_type = 'Purchase' where case_type = 'purchase';");
    }
}
