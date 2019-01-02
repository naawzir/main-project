<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DateFieldsTcpPricingStructures extends Migration
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

        if (Schema::hasColumn('fee_structures', 'date_created')) return;
        if (Schema::hasColumn('fee_structures', 'date_updated')) return;

        Schema::table('fee_structures', function (Blueprint $table) {

            $table->integer('date_created')->unsigned()->default(0);
            $table->integer('date_updated')->unsigned()->default(0);

        });

        $query = "UPDATE `fee_structures` SET `date_created`=UNIX_TIMESTAMP()";
        DB::statement($query);

        $query = "UPDATE `fee_structures` SET `date_updated`=UNIX_TIMESTAMP()";
        DB::statement($query);

    }

}
