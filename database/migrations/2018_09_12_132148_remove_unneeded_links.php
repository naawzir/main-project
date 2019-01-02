<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnneededLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_fees', function(Blueprint $table) {
            $table->dropColumn('agency_id');
        });
    
        Schema::table('fee_structures', function(Blueprint $table) {
            $table->dropColumn('agent_referral_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additional_fees', function(Blueprint $table) {
            $table->integer('agency_id')->length(11)->default(0);
        });
    
        Schema::table('fee_structures', function(Blueprint $table) {
            $table->double('agent_referral_fee')->default(0);
        });
    }
}
