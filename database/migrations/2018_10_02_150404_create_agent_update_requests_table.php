<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentUpdateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_update_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('case_id');
            $table->text('recipient_email');
            $table->text('message');
            $table->integer('date_created');
            $table->integer('date_updated');
            $table->text('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_update_requests');
    }
}
