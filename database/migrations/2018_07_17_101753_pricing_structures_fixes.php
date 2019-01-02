<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PricingStructuresFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->unsignedDecimal('legal_fee', 13, 2)->default(0)->change();
            $table->unsignedDecimal('referral_fee', 13, 2)->default(0)->change();
            $table->unsignedDecimal('agent_referral_fee', 13, 2)->default(0)->change();
            $table->boolean('active')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->integer('active')->length(1)->default(1)->change();
            $table->decimal('agent_referral_fee', 13, 2)->default(0)->change();
            $table->decimal('referral_fee', 13, 2)->default(0)->change();
            $table->decimal('legal_fee', 13, 2)->default(0)->change();
        });
    }
}
