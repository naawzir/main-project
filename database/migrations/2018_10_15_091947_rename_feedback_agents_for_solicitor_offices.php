<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameFeedbackAgentsForSolicitorOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('feedback_agents_for_solicitor_offices', 'solicitor_id')) return;
        Schema::table('feedback_agents_for_solicitor_offices', function (Blueprint $table) {
            $table->renameColumn('solicitor_office_id', 'solicitor_id');

            
        });

        if (Schema::hasTable('feedback_agents_for_solicitors')) return;
        Schema::rename('feedback_agents_for_solicitor_offices', 'feedback_agents_for_solicitors');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('feedback_agents_for_solicitor_offices', 'solicitor_office_id')) return;
        Schema::table('feedback_agents_for_solicitor_offices', function (Blueprint $table) {
            $table->renameColumn('solicitor_id', 'solicitor_office_id');
        });

        if (Schema::hasTable('feedback_agents_for_solicitor_offices')) return;
        Schema::rename('feedback_agents_for_solicitors', 'feedback_agents_for_solicitor_offices');
    }
}
