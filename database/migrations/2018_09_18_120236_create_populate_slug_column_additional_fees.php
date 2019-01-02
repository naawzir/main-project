<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\AdditionalFee;
use Webpatser\Uuid\Uuid;

class CreatePopulateSlugColumnAdditionalFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('additional_fees', 'slug')) return;
        DB::statement("ALTER TABLE `additional_fees` ADD COLUMN `slug` CHAR(36) NOT NULL AFTER `id`;");

        $fees = AdditionalFee::all();
        foreach($fees as $fee) {
            $fee->slug = Uuid::generate(4)->string;
            $fee->save();
        }

        DB::statement("ALTER TABLE `additional_fees` ADD UNIQUE INDEX `slug` (`slug`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    /*public function down()
    {
        if (Schema::hasColumn('fee_structures', 'slug'))
        {
            Schema::table('fee_structures', function (Blueprint $table)
            {
                $table->dropColumn('slug');
            });
        }
    }*/
}
