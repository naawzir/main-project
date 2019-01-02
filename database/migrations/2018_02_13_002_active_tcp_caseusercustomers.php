<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class ActiveTcpCaseusercustomers extends Migration
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
        Schema::table('case_user_customers', function ($table) {
            $table->unsignedInteger('active')->default(1);
        });
    }
}
