<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class NullableEmailTcpUsers extends Migration
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
            $table->string('email', 64)->nullable()->default(null)->change();
        });
    }
}
