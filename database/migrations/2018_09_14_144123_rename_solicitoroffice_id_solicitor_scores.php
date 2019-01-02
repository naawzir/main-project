<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSolicitorofficeIdSolicitorScores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('solicitor_scores', 'solicitor_office_id')) return;
        DB::statement("ALTER TABLE `solicitor_scores` 
        CHANGE COLUMN `solicitoroffice_id` `solicitor_office_id` INT(10) UNSIGNED NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('solicitor_scores', 'solicitoroffice_id')) return;
        DB::statement("ALTER TABLE `solicitor_scores`
	    CHANGE COLUMN `solicitor_office_id` `solicitoroffice_id` INT(10) UNSIGNED NULL DEFAULT NULL");
    }
}
