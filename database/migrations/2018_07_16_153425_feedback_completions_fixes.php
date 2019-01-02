<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeedbackCompletionsFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback_completions', function (Blueprint $table) {
            $table->unsignedInteger('date_completed')->length(10)->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedback_completions', function (Blueprint $table) {
            $table->integer('date_completed')->length(11)->default(null)->change();
        });
    }
}
