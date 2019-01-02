<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSolicitorIdToSolicitorOfficeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('agency_solicitor_partnerships', 'solicitor_office_id')) return;
        if (Schema::hasColumn('agency_solicitor_partnerships', 'solicitor_id')) {
            Schema::table('agency_solicitor_partnerships', function (Blueprint $table) {
                $table->renameColumn('solicitor_id', 'solicitor_office_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('agency_solicitor_partnerships', 'solicitor_id')) return;
        Schema::table('agency_solicitor_partnerships', function (Blueprint $table) {
            $table->renameColumn('solicitor_office_id', 'solicitor_id');
        });
    }
}
