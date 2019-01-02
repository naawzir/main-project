<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConveyancingCasesHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('conveyancing_cases_history')) {
            DB::statement("CREATE TABLE `conveyancing_cases_history` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `conveyancing_case_id` INT(10) UNSIGNED NULL DEFAULT NULL,
                `data` TEXT NULL,
                `date_created` INT(10) UNSIGNED NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                INDEX `conveyancing_case_id` (`conveyancing_case_id`)
                )
            ENGINE=InnoDB");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conveyancing_cases_history');
    }
}
