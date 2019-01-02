<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DisbursementsFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disbursements', function (Blueprint $table) {
            $table->boolean('both_vendors')->default(0)->change();
            $table->boolean('active')->default(1)->change();
            $table->boolean('standard')->default(0)->change();
            $table->mediumText('subtitle')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disbursements', function (Blueprint $table) {
            $table->text('subtitle')->change();
            $table->unsignedInteger('standard')->length(10)->default(0)->change();
            $table->unsignedInteger('active')->length(10)->default(1)->change();
            $table->unsignedInteger('both_vendors')->length(10)->default(0)->change();
        });
    }
}
