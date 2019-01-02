<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySolicitorScores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitor_scores', function(Blueprint $table) {
            $table->renameColumn('solicitor_id', 'solicitoroffice_id');
            DB::statement("UPDATE `solicitor_scores` SET score = 10 WHERE score = 'na'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitor_scores', function(Blueprint $table) {
            $table->renameColumn('solicitoroffice_id', 'solicitor_id');
        });
    }
}
