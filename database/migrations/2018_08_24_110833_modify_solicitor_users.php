<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySolicitorUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitor_users', function (Blueprint $table) {
        
            $table->string('password')->length(32)->nullable(false)->default('');
            $table->string('activation_code')->length(100);
            $table->integer('sms_marketing_opt_in')->length(1)->default(0);
            $table->integer('email_marketing_opt_in')->length(1)->default(0);
            $table->integer('last_login')->length(11)->default(0);
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
            $table->dropColumn('password');
            $table->dropColumn('activation_code');
            $table->dropColumn('sms_marketing_opt_in');
            $table->dropColumn('email_marketing_opt_in');
            $table->dropColumn('last_login');
        });
    }
}
