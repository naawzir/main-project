<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class NullableAgencyBranchIdUserAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up()
    {
        Schema::table('user_agents', function ($table) {
            $table->integer('agency_branch_id')->nullable()->change();
        });
    }
}
