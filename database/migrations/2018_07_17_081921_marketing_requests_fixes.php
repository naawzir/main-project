<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketingRequestsFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketing_requests', function (Blueprint $table) {
            $table->boolean('quote')->default(0)->change();
            $table->text('other_information')->change();
            $table->boolean('use_ad')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketing_requests', function (Blueprint $table) {
            $table->integer('use_ad')->default(0);
            $table->string('other_information')->default(null)->change();
            $table->integer('quote')->length(11)->default(0)->change();
        });
    }
}
