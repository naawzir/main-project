<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CaseFeesCorrections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (Schema::hasTable('case_fees')) return;
        Schema::table('case_fees', function (Blueprint $table) {
            $table->unsignedInteger('date_created')->length(10)->default(null)->change();
            $table->unsignedInteger('date_updated')->length(10)->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_fees', function (Blueprint $table) {
            $table->integer('date_created')->length(11)->default(0)->change();
            $table->integer('date_updated')->length(11)->default(0)->change();
        });
    }
}
