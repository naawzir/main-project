<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DateFieldsTcpUserCustomers extends Migration
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

        if (Schema::hasColumn('user_customers', 'date_created')) return;
        if (Schema::hasColumn('user_customers', 'date_updated')) return;

        Schema::table('user_customers', function (Blueprint $table) {
            $table->integer('date_created')->unsigned()->default(0);
            $table->integer('date_updated')->unsigned()->default(0);
        });

        $query = "UPDATE `user_customers` SET `date_created`=UNIX_TIMESTAMP()";
        DB::statement($query);

        $query = "UPDATE `user_customers` SET `date_updated`=UNIX_TIMESTAMP()";
        DB::statement($query);
    }

}
