<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InstructionsFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instructions', function (Blueprint $table) {
            $table->unsignedInteger('date_sent')->length(10)->default(null)->change();
            $table->boolean('panelled')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructions', function (Blueprint $table) {
            $table->unsignedInteger('panelled')->length(10)->default(1)->change();
            $table->integer('date_sent')->length(11)->default(0)->change();
        });
    }
}
