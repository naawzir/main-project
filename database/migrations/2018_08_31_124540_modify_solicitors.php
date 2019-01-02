<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySolicitors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_fees', function(Blueprint $table) {
            $table->renameColumn('solicitor_id', 'solicitoroffice_id');
        });
    
        Schema::table('solicitors', function(Blueprint $table) {
            $table->string('status')->length(12)->default('Onboarding');
            $table->dropColumn('active');
        });
    
        Schema::table('solicitor_offices', function(Blueprint $table) {
            $table->string('status')->length(12)->default('Onboarding');
            $table->dropColumn('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additional_fees', function(Blueprint $table) {
            $table->renameColumn('solicitoroffice_id', 'solicitor_id');
        });
    
        Schema::table('solicitors', function(Blueprint $table) {
            $table->dropColumn('status');
            $table->integer('active')->length(1)->default(0);
        });
    
        Schema::table('solicitor_offices', function(Blueprint $table) {
            $table->dropColumn('status');
            $table->integer('active')->length(1)->default(0);
        });
    }
}
