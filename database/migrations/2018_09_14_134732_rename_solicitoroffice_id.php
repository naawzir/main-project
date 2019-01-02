<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSolicitorofficeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('additional_fees', 'solicitor_office_id')) return;
        DB::statement("ALTER TABLE `additional_fees` 
        CHANGE COLUMN `solicitoroffice_id` `solicitor_office_id` INT(10) UNSIGNED NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('additional_fees', 'solicitoroffice_id')) return;
        DB::statement("ALTER TABLE `additional_fees`
	    CHANGE COLUMN `solicitor_office_id` `solicitoroffice_id` INT(10) UNSIGNED NULL DEFAULT NULL");
    }
}
