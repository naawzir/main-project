<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterServiceCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('service_collections')) {
            /*DB::statement("ALTER TABLE `service_collections`
                CHANGE COLUMN `conveyancing_id` `target_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `id`,
                ADD COLUMN `target_type` VARCHAR(50) NULL DEFAULT NULL AFTER `target_id`;");*/

            if(
                !Schema::hasColumn('service_collections', 'target_id') &&
                Schema::hasColumn('service_collections', 'conveyancing_id')
            ) {
                Schema::table('service_collections', function (Blueprint $table) {
                    $table->renameColumn('conveyancing_id', 'target_id');
                });
            }

            if(!Schema::hasColumn('service_collections', 'target_type')) {
                Schema::table('service_collections', function (Blueprint $table) {
                    $table->string('target_type')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
