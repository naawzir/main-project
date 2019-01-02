<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConveyancingCase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('conveyancing_cases')) return;

            Schema::create('conveyancing_cases', function (Blueprint $table) {
                $table->increments('id');
                $table->string('type', 15)->nullable()->default(null);
                $table->string('status', 30)->nullable()->default(null);
                $table->string('created_by', 40)->nullable()->comment('Used to be source');;
                $table->unsignedInteger('solicitor_id')->default(null);
                $table->unsignedInteger('solicitor_office_id')->default(null);
                $table->unsignedInteger('solicitor_user_id')->default(null);
                $table->unsignedInteger('fee_structure_id')->default(null);
                $table->string('lead_source', 40)->nullable();
                $table->decimal('price', 13, 2)->default('0.00');
                $table->string('tenure', 50)->nullable(false);
                $table->boolean('alerts')->default(0);
                $table->decimal('discount', 13,2)->default('0.00');
                $table->boolean('searches_required')->default(0);
                $table->boolean('mortgage')->default(0);
                $table->boolean('mortgage_new')->default(0);
                $table->boolean('mortgage_redeem')->default(0);
                $table->boolean('first_time_buyer')->default(0);
                $table->boolean('new_build')->default(0);
                $table->boolean('second_home')->default(0);
                $table->boolean('aml_fees_paid')->default(0);
                $table->boolean('all_aml_searches_complete')->default(0);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conveyancing_case');
    }
}
