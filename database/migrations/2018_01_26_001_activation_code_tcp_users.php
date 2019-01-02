<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class ActivationCodeTcpUsers extends Migration
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
            $table->string('activation_code', 100)->nullable()->change();
        });
    }
}
