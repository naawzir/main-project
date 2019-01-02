<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDobColumnCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('customers', 'dob')) return;
        DB::statement("ALTER TABLE `customers`
        ADD COLUMN `dob` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `phone`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('customers', 'dob')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('dob');
            });
        }
    }
}
