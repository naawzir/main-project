<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_fees', function (Blueprint $table) {
            $table->dropForeign('FK_tcp_additional_fees_tcp_agencies');
            $table->dropForeign('FK_tcp_additional_fees_tcp_solicitors');
        });
    }
}
