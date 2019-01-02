<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThirdPartyDisbursements extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('third_party_disbursements')) {
            
            Schema::create('third_party_disbursements', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('active')->default(1);
                $table->string('name');
                $table->string('transaction');
                $table->string('type');
                $table->decimal('cost', 13, 2)->default('0.00');
                $table->unsignedInteger('date_created')->default('0');
                $table->unsignedInteger('date_updated')->default('0');
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('third_party_disbursements');
    }
}
