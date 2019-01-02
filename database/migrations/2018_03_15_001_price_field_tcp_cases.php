<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class PriceFieldTcpCases extends Migration
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
        Schema::table('cases', function ($table) {

            $table->decimal('price', 13, 2)->nullable()->default(null)->change();

        });
    }
}
