<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSolicitorScores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('feedback_agents_for_solicitor_offices')) return;
        Schema::rename('solicitor_scores', 'feedback_agents_for_solicitor_offices');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('solicitor_scores')) return;
        Schema::rename('feedback_agents_for_solicitor_offices', 'solicitor_scores');
    }
}
