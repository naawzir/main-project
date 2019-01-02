<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Disbursement;
use Webpatser\Uuid\Uuid;

class CreatePopulateSlugColumnDisbursements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('third_party_disbursements', 'slug')) return;
        DB::statement("ALTER TABLE `third_party_disbursements` ADD COLUMN `slug` CHAR(36) NOT NULL AFTER `id`;");

        $records = Disbursement::all();
        foreach($records as $record) {
            $record->slug = Uuid::generate(4)->string;
            $record->save();
        }

        DB::statement("ALTER TABLE `third_party_disbursements` ADD UNIQUE INDEX `slug` (`slug`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('third_party_disbursements', 'slug'))
        {
            Schema::table('third_party_disbursements', function (Blueprint $table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
