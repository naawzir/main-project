<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackCustomersForSolicitorOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('feedback_customers_for_solicitor_offices')) {
            DB::statement("CREATE TABLE `feedback_customers_for_solicitor_offices` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `solicitor_office_id` INT(10) UNSIGNED NOT NULL,
                `customer_id` INT(10) UNSIGNED NOT NULL,
                `q1` VARCHAR(10) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `check_string` CHAR(32) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
                `date_completed` INT(10) UNSIGNED NULL DEFAULT NULL,
                `date_created` INT(10) UNSIGNED NOT NULL,
                `date_updated` INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (`id`)
            )
            COLLATE='utf8mb4_unicode_ci'
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
        Schema::dropIfExists('feedback_customers_for_solicitor_offices');
    }
}
