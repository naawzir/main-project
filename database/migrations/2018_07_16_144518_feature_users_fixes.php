<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeatureUsersFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	
        Schema::table('feature_users', function (Blueprint $table) {
            $table->boolean('skipped')->default(0)->comment('0 - No skip, 1 - Skipped')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feature_users', function (Blueprint $table) {
            $table->unsignedTinyInteger('skipped')->default(0)->comment('0 - No skip, 1 - Skipped, NULL - not possible to skip.')->change();
        });
    }
}
