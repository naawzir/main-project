<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketingResourcesFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketing_resources', function (Blueprint $table) {
            $table->boolean('allow_download')->default(0)->change();
            $table->unsignedInteger('position')->length(5)->change();
            $table->boolean('available')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketing_resources', function (Blueprint $table) {
            $table->unsignedInteger('available')->default(1)->change();
            $table->unsignedInteger('position')->length(11)->change();
            $table->unsignedInteger('allow_download')->default(0)->change();
        });
    }
}
