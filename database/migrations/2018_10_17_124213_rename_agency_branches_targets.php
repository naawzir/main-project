<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameAgencyBranchesTargets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('targets_agency_branches')) return;
        Schema::rename('agency_branches_targets', 'targets_agency_branches');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('agency_branches_targets')) return;
        Schema::rename('targets_agency_branches', 'agency_branches_targets');
    }
}
