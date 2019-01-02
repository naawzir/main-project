<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DateUpdatedFieldTcpLastcasereference extends Migration
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

        if (Schema::hasColumn('last_case_reference', 'date_created')) return;
        if (Schema::hasColumn('last_case_reference', 'date_updated')) return;
        Schema::table('last_case_reference', function (Blueprint $table) {
            $table->integer('date_created')->unsigned()->default(0);
            $table->integer('date_updated')->unsigned()->default(0);
        });

        $query = "UPDATE `last_case_reference` SET `date_created`=UNIX_TIMESTAMP()";
        DB::statement($query);

        $query = "UPDATE `last_case_reference` SET `date_updated`=UNIX_TIMESTAMP()";
        DB::statement($query);
    }

}
