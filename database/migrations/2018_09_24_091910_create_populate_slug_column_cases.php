<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ConveyancingCase;
use App\Cases;
use Webpatser\Uuid\Uuid;

class CreatePopulateSlugColumnCases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('cases', 'slug')) return;
        DB::statement("ALTER TABLE `cases` ADD COLUMN `slug` CHAR(36) NOT NULL AFTER `id`;");

        $records = Cases::all();
        foreach($records as $record) {
            $record->slug = Uuid::generate(4)->string;
            $record->save();
        }

        DB::statement("ALTER TABLE `cases` ADD UNIQUE INDEX `slug` (`slug`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('cases', 'slug'))
        {
            Schema::table('cases', function (Blueprint $table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
