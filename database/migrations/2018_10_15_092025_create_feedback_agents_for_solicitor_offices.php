<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackAgentsForSolicitorOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TABLE `feedback_agents_for_solicitor_offices` (
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id_agent` INT(10) UNSIGNED NOT NULL,
            `solicitor_office_id` INT(10) UNSIGNED NULL DEFAULT NULL,
            `score` VARCHAR(3) NULL DEFAULT NULL,
            `date_created` INT(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`id`),
            INDEX `idx_tcp_solicitor_scores__user_id_agent` (`user_id_agent`),
            INDEX `idx_tcp_solicitor_scores__solicitor_id` (`solicitor_office_id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        AUTO_INCREMENT=577
        ;");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback_agents_for_solicitor_offices');
    }
}
