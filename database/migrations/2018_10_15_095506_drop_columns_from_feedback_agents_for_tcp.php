<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromFeedbackAgentsForTcp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('feedback_agents_for_tcp', 'q2_score')) {
            DB::statement("ALTER TABLE feedback_agents_for_tcp 
            DROP COLUMN q2_score;");
        }

        if (Schema::hasColumn('feedback_agents_for_tcp', 'q1_comments')) {
            DB::statement("ALTER TABLE feedback_agents_for_tcp 
            DROP COLUMN q1_comments;");
        }

        if (Schema::hasColumn('feedback_agents_for_tcp', 'q2_comments')) {
            DB::statement("ALTER TABLE feedback_agents_for_tcp 
            DROP COLUMN q2_comments;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
