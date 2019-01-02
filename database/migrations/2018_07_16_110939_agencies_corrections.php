<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgenciesCorrections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (Schema::hasTable('agencies')) return;
        Schema::table('agencies', function (Blueprint $table) {
            $table->boolean('active')->default(1)->change();
            $table->mediumText('style')->nullable()->default(null)->change();
            $table->boolean('use_custom_points')->default(0)->change();
            $table->boolean('allow_comparison')->default(1)->change();
            $table->foreign('fee_structure_id', 'FK_tcp_agency_tcp_fee_structures')
                ->references('id')
                ->on('fee_structures')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->integer('allow_comparison')->default(1)->change();
            $table->unsignedInteger('use_custom_points')->nullable()->default(0)->change();
            $table->text('style')->nullable()->default(null)->change();
            $table->unsignedInteger('active')->default(1)->change();
            $table->dropForeign('FK_tcp_agency_tcp_fee_structures');
        });
    }
}
