<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserAgentsFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_agents', function(Blueprint $table) {
            $table->boolean('valuer')->default(0)->change();
            $table->boolean('registered_for_points')->default(0)->change();
            $table->boolean('show_survey')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_agents', function(Blueprint $table) {
            $table->unsignedInteger('show_survey')->length(10)->default(1)->change();
            $table->unsignedInteger('registered_for_points')->length(10)->default(0)->change();
            $table->integer('valuer')->length(1)->default(0)->change();
        });
    }
}
