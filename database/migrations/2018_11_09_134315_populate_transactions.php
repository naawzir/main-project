<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO transactions 
        (
            active,
            status,
            type,
            reference,
            address_id,
            staff_user_id,
            agency_id,
            agency_branch_id,
            agent_user_id,
            date_created,
            date_updated
        )
        SELECT 
            active, 
            status,
            type,
            reference,
            address_id,
            user_id_staff,
            agency_id,
            agency_branch_id,
            user_id_agent,
            date_created,
            date_updated
        FROM
        conveyancing_cases 
        ORDER BY id ASC");
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
