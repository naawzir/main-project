<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCasesToConveyancingCases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('conveyancing_cases');
        if (Schema::hasTable('cases')) {
            Schema::rename('cases', 'conveyancing_cases');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('conveyancing_cases')) {
            Schema::rename('conveyancing_cases', 'cases');
        }
    }
}
