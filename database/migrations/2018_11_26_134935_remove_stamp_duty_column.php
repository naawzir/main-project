<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveStampDutyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_fees', function (Blueprint $table) {
            $table->dropColumn('stamp_duty_land_tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additional_fees', function (Blueprint $table) {
            $table->decimal('stamp_duty_land_tax', 13, 2)->default('0.00');
        });
    }
}
