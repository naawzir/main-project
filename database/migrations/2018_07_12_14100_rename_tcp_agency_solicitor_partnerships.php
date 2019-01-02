<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTcpAgencySolicitorPartnerships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('agency_solicitor_panel') && Schema::hasTable('tcp_agency_solicitor_partnerships')) {

            Schema::rename('tcp_agency_solicitor_partnerships', 'agency_solicitor_panel');

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('tcp_agency_solicitor_partnerships') && Schema::hasTable('agency_solicitor_panel')) {

            Schema::rename('agency_solicitor_panel', 'tcp_agency_solicitor_partnerships');

        }

    }

}
