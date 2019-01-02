<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferralFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `solicitor_offices`
        ADD COLUMN `referral_fee` DECIMAL(13,2) UNSIGNED NULL DEFAULT '0.00' AFTER `status`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `solicitor_offices`
        DROP COLUMN `referral_fee`");
    }
}
