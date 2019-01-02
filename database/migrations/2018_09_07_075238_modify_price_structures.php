<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPriceStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_structures', function(Blueprint $table) {
            $table->double('price_to')->default(0);
            $table->renameColumn('start', 'price_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_structures', function(Blueprint $table) {
            $table->dropColumn('price_to');
            $table->renameColumn('price_from', 'start');
        });
    }
}
