<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCaseIdFeedbackCustomersForSolicitorOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('feedback_customers_for_solicitor_offices', 'case_id')) return;
        DB::statement("ALTER TABLE `feedback_customers_for_solicitor_offices`
        ADD COLUMN `case_id` INT(10) UNSIGNED NOT NULL AFTER `id`");

        DB::statement("ALTER TABLE `feedback_customers_for_solicitor_offices`
        ADD INDEX `case_id` (`case_id`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('feedback_customers_for_solicitor_offices', 'case_id')) {
            DB::statement("ALTER TABLE feedback_customers_for_solicitor_offices 
            DROP COLUMN case_id");
        }
    }
}
