<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameFeedbackCompletions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('feedback_customers_for_tcp')) return;
        Schema::rename('feedback_completions', 'feedback_customers_for_tcp');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('feedback_completions')) return;
        Schema::rename('feedback_customers_for_tcp', 'feedback_completions');
    }
}
