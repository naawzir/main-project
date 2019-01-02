<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateAgencyBranchesTargets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO agency_branches_targets (agency_id, agency_branch_id, date_created, date_updated, date_from, target)
            SELECT
                a.id,
                b.id,
                '1533081600',
                '1533081600',
                '1533081600',
                20
            from agencies as a
            inner join agency_branches as b on b.agency_id = a.id;");

        DB::statement("INSERT INTO agency_branches_targets (agency_id, agency_branch_id, date_created, date_updated, date_from, target)
            SELECT
                a.id,
                b.id,
                '1535760000',
                '1535760000',
                '1535760000',
                20
            from agencies as a
            inner join agency_branches as b on b.agency_id = a.id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("TRUNCATE TABLE agency_branches_targets");
    }
}
