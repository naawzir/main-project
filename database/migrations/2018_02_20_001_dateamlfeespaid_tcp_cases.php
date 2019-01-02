<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DateamlfeespaidTcpCases extends Migration
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

        if (Schema::hasColumn('cases', 'date_aml_fees_paid')) return;

        Schema::table('cases', function (Blueprint $table) {
            $table->integer('date_aml_fees_paid')->unsigned()->nullable();
        });

    }

}
