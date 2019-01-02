<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TasksArchiveFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks_archive', function(Blueprint $table) {
            $table->unsignedSmallInteger('follow_up')->length(1)->default(0)->comment('0 => not required,\\n1 => required,\\n2 => required+completed')->change();
            $table->unsignedInteger('date_time')->length(10)->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks_archive', function(Blueprint $table) {
            $table->unsignedInteger('date_time')->length(11)->default(null)->change();
            $table->integer('follow_up')->length(11)->default(null)->comment('0 => not required,\\n1 => required,\\n2 => required+completed')->change();
        });
    }
}
