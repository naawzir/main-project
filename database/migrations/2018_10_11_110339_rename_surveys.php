<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSurveys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('feedback_agents_for_tcp')) return;
        Schema::rename('surveys', 'feedback_agents_for_tcp');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('surveys')) return;
        Schema::rename('feedback_agents_for_tcp', 'surveys');
    }
}
