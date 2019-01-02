<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCasesTypeValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = "update cases
                set type = 'sale'
                where type = 'selling';";

        DB::statement($query);

        $query = "update cases
                set type = 'purchase'
                where type = 'buying';";

        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $query = "update cases
                set type = 'selling'
                where type = 'sale';";

        DB::statement($query);

        $query = "update cases
                set type = 'buying'
                where type = 'purchase';";

        DB::statement($query);
    }
}
