<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class NullableUsernameTcpUsers extends Migration
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
            $table->string('username', 50)->nullable()->default(null)->change();
        });
    }
}
