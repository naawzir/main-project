<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCasesInstructedUnpanelled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("update cases
        set status = 'instructed_unpanelled'
        where panelled = 0
        and status = 'instructed'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("update cases
        set status = 'instructed'
        where status = 'instructed_unpanelled'");
    }
}
