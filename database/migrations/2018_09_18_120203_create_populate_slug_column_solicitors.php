<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Solicitor;
use Webpatser\Uuid\Uuid;

class CreatePopulateSlugColumnSolicitors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('solicitors', 'slug')) return;
        DB::statement("ALTER TABLE `solicitors` ADD COLUMN `slug` CHAR(36) NOT NULL AFTER `id`;");

        $solicitors = Solicitor::all();
        foreach($solicitors as $solicitor) {
            $solicitor->slug = Uuid::generate(4)->string;
            $solicitor->save();
        }

        DB::statement("ALTER TABLE `solicitors` ADD UNIQUE INDEX `slug` (`slug`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('solicitors', 'slug'))
        {
            Schema::table('solicitors', function (Blueprint $table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
