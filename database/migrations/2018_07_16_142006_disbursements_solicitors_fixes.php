<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DisbursementsSolicitorsFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disbursements_solicitors', function (Blueprint $table) {
            $table->decimal('cost', 13,2)->default(0)->change();
            $table->boolean('active')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disbursements_solicitors', function (Blueprint $table) {
            $table->integer('active')->length(11)->default(1)->change();
            $table->double('cost', 8,2)->default(0)->change();
        });
    }
}
