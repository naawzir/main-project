<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitorDisbursements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE `solicitor_disbursements` (
                `solicitor_office_id` INT(10) UNSIGNED NOT NULL,
                `disbursement_id` INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (`solicitor_office_id`, `disbursement_id`),
                INDEX `solicitor_office_id` (`solicitor_office_id`),
                INDEX `disbursement_id` (`disbursement_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitor_disbursements');
    }
}
