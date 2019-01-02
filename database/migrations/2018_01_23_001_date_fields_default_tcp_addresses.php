<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DateFieldsDefaultTcpAddresses extends Migration
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
        Schema::table('addresses', function (Blueprint $table) {
            $table->integer('date_created')->default(0)->change();
            $table->integer('date_updated')->default(0)->change();
        });
    }
}
