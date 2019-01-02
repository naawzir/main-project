<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\LegalFee;
use Webpatser\Uuid\Uuid;

class CreatePopulateSlugColumnFeeStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('legal_fees', 'slug')) return;
        DB::statement("ALTER TABLE `legal_fees` ADD COLUMN `slug` CHAR(36) NOT NULL AFTER `id`;");

        $feeStructures = LegalFee::all();
        foreach($feeStructures as $feeStructure) {
            $feeStructure->slug = Uuid::generate(4)->string;
            $feeStructure->save();
        }

        DB::statement("ALTER TABLE `legal_fees` ADD UNIQUE INDEX `slug` (`slug`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*if (Schema::hasColumn('pricing_structures', 'slug'))
        {
            Schema::table('pricing_structures', function (Blueprint $table)
            {
                $table->dropColumn('slug');
            });
        }*/
    }
}
