<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameFeeStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('legal_fees') && Schema::hasTable('fee_structures')) {
            Schema::rename('fee_structures', 'legal_fees');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('fee_structures') && Schema::hasTable('legal_fees')) {
            Schema::rename('legal_fees', 'fee_structures');
        }
    }
}
