<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionServiceCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('transaction_service_collections')) {
            DB::statement("CREATE TABLE `transaction_service_collections` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL,
                `service_collection_id` INT(10) UNSIGNED NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                INDEX `transaction_id` (`transaction_id`),
                INDEX `service_collection_id` (`service_collection_id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_service_collections');
    }
}
