<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSolicitorUrlSolicitors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('solicitors', 'url')) return;
        DB::statement("ALTER TABLE `solicitors` ADD COLUMN `url` VARCHAR(250) NULL DEFAULT NULL AFTER `email`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('solicitors', 'url')) return;
        DB::statement("ALTER TABLE `solicitors` DROP COLUMN `url`;");
    }
}
