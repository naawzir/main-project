<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SolicitorOffice;
use Webpatser\Uuid\Uuid;

class CreatePopulateSlugColumnSolicitorOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('solicitor_offices', 'slug')) return;
        DB::statement("ALTER TABLE `solicitor_offices` ADD COLUMN `slug` CHAR(36) NOT NULL AFTER `id`;");

        $solicitoroffices = SolicitorOffice::all();
        foreach($solicitoroffices as $solicitoroffice) {
            $solicitoroffice->slug = Uuid::generate(4)->string;
            $solicitoroffice->save();
        }

        DB::statement("ALTER TABLE `solicitor_offices` ADD UNIQUE INDEX `slug` (`slug`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('solicitor_offices', 'slug'))
        {
            Schema::table('solicitor_offices', function (Blueprint $table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
