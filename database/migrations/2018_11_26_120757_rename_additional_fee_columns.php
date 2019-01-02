<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameAdditionalFeeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_fees', function (Blueprint $table) {
            $table->renameColumn('subfee_mortgage', 'mortgage');
            $table->renameColumn('subfee_redemp_mortgage', 'mortgage_redemption');
            $table->renameColumn('subfee_leasehold', 'leasehold');
            $table->renameColumn('subfee_sdlt', 'stamp_duty_land_tax');
            $table->renameColumn('subfee_archive', 'archive');
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
            $table->renameColumn('mortgage', 'subfee_mortgage');
            $table->renameColumn('mortgage_redemption', 'subfee_redemp_mortgage');
            $table->renameColumn('leasehold', 'subfee_leasehold');
            $table->renameColumn('stamp_duty_land_tax', 'subfee_sdlt');
            $table->renameColumn('archive', 'subfee_archive');
        });
    }
}
