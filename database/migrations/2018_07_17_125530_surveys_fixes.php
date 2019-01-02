<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SurveysFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function(Blueprint $table) {
            $table->unsignedSmallInteger('q1_score')->length(2)->default(0)->change();
            $table->unsignedSmallInteger('q2_score')->length(2)->default(0)->change();
            $table->text('q1_comments')->change();
            $table->text('q2_comments')->change();
            $table->unsignedInteger('date_completed')->length(10)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surveys', function(Blueprint $table) {
            $table->unsignedInteger('date_completed')->length(11)->change();
            $table->string('q2_comments')->default('')->change();
            $table->string('q1_comments')->default('')->change();
            $table->integer('q2_score')->length(2)->default(0)->change();
            $table->integer('q1_score')->length(2)->default(0)->change();
        });
    }
}
