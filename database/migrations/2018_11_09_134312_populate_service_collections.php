<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateServiceCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO service_collections (target_id, target_type)
            SELECT 
                id, 
                'conveyancing_case'
            FROM
            conveyancing_cases ORDER BY id ASC"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('service_collections')->truncate();
    }
}
