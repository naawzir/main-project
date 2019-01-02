<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class NullablePhoneTcpUsers extends Migration
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
        Schema::table('users', function ($table) {
            $table->string('phone', 64)->nullable()->change();
            $table->string('phone_other', 64)->nullable()->change();
        });
    }
}
