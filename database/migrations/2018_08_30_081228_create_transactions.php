<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('transactions')) return;

         Schema::create('transactions', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('active')->default(1);
                $table->string('status', 10)->nullable()->default(null);
                $table->string('type', 30)->nullable()->default(null);
                $table->string('reference', 20)->nullable()->default(null);
                $table->unsignedInteger('staff_user_id')->nullable()->default(null);
                $table->unsignedInteger('agency_id')->nullable()->default(null);
                $table->unsignedInteger('agency_branch_id')->nullable()->default(null);
                $table->unsignedInteger('agent_user_id')->nullable()->default(null);
                $table->unsignedInteger('service_collection_id')->nullable()->default(null);
                $table->unsignedInteger('date_created')->default('0');
                $table->unsignedInteger('date_updated')->default('0');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
