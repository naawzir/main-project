<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MilestonesFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tm_milestones', function(Blueprint $table) {
            $table->unsignedSmallInteger('number')->length(3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tm_milestones', function(Blueprint $table) {
            $table->integer('number')->length(3)->default(0)->change();
        });
    }
}
