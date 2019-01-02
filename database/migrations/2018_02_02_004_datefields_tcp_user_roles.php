<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatefieldsTcpUserRoles extends Migration
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

        if (Schema::hasColumn('user_roles', 'date_created')) return;
        if (Schema::hasColumn('user_roles', 'date_updated')) return;
        Schema::table('user_roles', function (Blueprint $table) {
            $table->integer('date_created')->unsigned()->default(0);
            $table->integer('date_updated')->unsigned()->default(0);
        });

        $query = "UPDATE `user_roles` SET `date_created`=UNIX_TIMESTAMP()";
        DB::statement($query);

        $query = "UPDATE `user_roles` SET `date_updated`=UNIX_TIMESTAMP()";
        DB::statement($query);
    }

}
