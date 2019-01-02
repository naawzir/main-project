<?php

use App\Document;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopulateSlugColumnDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('documents', 'slug')) return;
        DB::statement("ALTER TABLE `documents` ADD COLUMN `slug` CHAR(36) NOT NULL AFTER `id`;");

        $records = Document::all();
        foreach($records as $record) {
            $record->slug = Uuid::generate(4)->string;
            $record->save();
        }

        DB::statement("ALTER TABLE `documents` ADD UNIQUE INDEX `slug` (`slug`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('documents', 'slug'))
        {
            Schema::table('documents', function (Blueprint $table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
