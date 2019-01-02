<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameNameOfficeNameSolicitorOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('solicitor_offices', 'office_name')) return;
        Schema::table('solicitor_offices', function (Blueprint $table) {
            $table->renameColumn('name', 'office_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('solicitor_offices', 'name')) return;
        Schema::table('solicitor_offices', function (Blueprint $table) {
            $table->renameColumn('office_name', 'name');
        });
    }
}
