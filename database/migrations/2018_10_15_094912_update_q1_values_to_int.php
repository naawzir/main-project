<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQ1ValuesToInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE feedback_customers_for_tcp
        SET q1 = 2 WHERE q1 = 'Poor'");
         DB::statement("UPDATE feedback_customers_for_tcp
        SET q1 = 5 WHERE q1 = 'Fair'");
        DB::statement("UPDATE feedback_customers_for_tcp
        SET q1 = 8 WHERE q1 = 'Good'");
        DB::statement("UPDATE feedback_customers_for_tcp
        SET q1 = 10 WHERE q1 = 'Excellent';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
