<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class UserIdAgentCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up()
    {
        Schema::table('cases', function ($table) {
            $table->integer('user_id_agent')->nullable()->change();
        });
    }
}
