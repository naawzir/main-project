<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnneededAdditionalFeeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_fees', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('active');
            $table->dropColumn('structure_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additional_fees', function (Blueprint $table) {
            $table->char('slug', 36)->unique();
            $table->boolean('active')->default(true);
            $table->string('structure_name', 250);
        });
    }
}
