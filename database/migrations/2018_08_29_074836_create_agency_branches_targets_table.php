<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgencyBranchesTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('agency_branches_targets')) return;

        DB::statement("CREATE TABLE `agency_branches_targets` (
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `agency_id` INT(10) UNSIGNED NULL DEFAULT '0',
            `agency_branch_id` INT(10) UNSIGNED NULL DEFAULT '0',
            `date_created` INT(10) UNSIGNED NOT NULL DEFAULT '0',
            `date_updated` INT(10) UNSIGNED NOT NULL DEFAULT '0',
            `date_from` INT(10) UNSIGNED NOT NULL DEFAULT '0',
            `target` INT(3) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agency_branches_targets');
    }
}
