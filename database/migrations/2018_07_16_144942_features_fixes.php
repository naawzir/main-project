<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeaturesFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (Schema::hasTable('features')) return;
        Schema::table('features', function (Blueprint $table) {
            $table->dropPrimary('name');

            $table->unsignedInteger('id')->length(10)->first();
            $table->string('name')->length(30)->change();
            $table->text('description')->change();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('features', function (Blueprint $table) {
            $table->dropPrimary('id');

            $table->unsignedInteger('name')->length(10)->autoIncrement()->change();
            $table->string('description')->change();
            $table->dropColumn('id');

            $table->primary('name');
        });
    }
}
