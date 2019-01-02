<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DocumentsFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	
        Schema::table('documents', function (Blueprint $table) {
            $table->string('tags')->default(null)->change();
            $table->boolean('do_regenerate')->default(0)->comment('This will be set to "1" so that any automaton generation process knows to regenerate it.')->change();
            $table->boolean('cf_upload')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->integer('cf_upload')->length(11)->default(0)->change();
            $table->unsignedInteger('do_regenerate')->length(10)->default(0)->comment('This will be set to "1" so that any automaton generation process knows to regenerate it.')->change();
            $table->text('tags')->change();
        });
    }
}
