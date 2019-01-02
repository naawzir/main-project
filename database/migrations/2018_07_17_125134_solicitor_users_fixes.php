<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SolicitorUsersFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitor_users', function(Blueprint $table) {
            $table->unsignedInteger('user_id')->length(10)->default(null)->after('id');
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
        Schema::table('solicitor_users', function(Blueprint $table) {
            $table->unsignedInteger('active')->length(10)->default(1)->change();
            $table->dropColumn('user_id');
        });
    }
}
