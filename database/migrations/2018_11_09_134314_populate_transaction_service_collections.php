<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateTransactionServiceCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO transaction_service_collections (transaction_id, service_collection_id)
            SELECT 
                id, 
                id
            FROM
            service_collections ORDER BY id ASC"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('transaction_service_collections')->truncate();
    }
}
