<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateColumnAgencySolicitorPanel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agency_solicitor_panel', function (Blueprint $table) {
            $table->unsignedInteger('date_created')->length(10)->nullable();
            $table->unsignedInteger('date_updated')->length(10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('agency_solicitor_panel', 'date_created')) {
            Schema::table('agency_solicitor_panel', function (Blueprint $table) {
                $table->dropColumn('date_created');
            });
        }

        if (Schema::hasColumn('agency_solicitor_panel', 'date_updated')) {
            Schema::table('agency_solicitor_panel', function (Blueprint $table) {
                $table->dropColumn('date_updated');
            });
        }
    }
}
