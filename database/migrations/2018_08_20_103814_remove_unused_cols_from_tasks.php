<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnusedColsFromTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (!Schema::hasColumn('tasks', 'data')) return;
	    Schema::table('tasks', function (Blueprint $table) {
		    $table->dropColumn('data');
	    });
	    
	    if (!Schema::hasColumn('tasks', 'date_time')) return;
	    Schema::table('tasks', function (Blueprint $table) {
		    $table->dropColumn('date_time');
	    });
    }
}
