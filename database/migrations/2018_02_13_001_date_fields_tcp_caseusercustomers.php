<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DateFieldsTcpCaseusercustomers extends Migration
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

        if (Schema::hasColumn('case_user_customers', 'date_created')) return;
        if (Schema::hasColumn('case_user_customers', 'date_updated')) return;
        Schema::table('case_user_customers', function (Blueprint $table) {
            $table->integer('date_created')->unsigned()->default(0);
            $table->integer('date_updated')->unsigned()->default(0);
        });

        $query = "UPDATE `case_user_customers` SET `date_created`=UNIX_TIMESTAMP()";
        DB::statement($query);

        $query = "UPDATE `case_user_customers` SET `date_updated`=UNIX_TIMESTAMP()";
        DB::statement($query);
    }

}
