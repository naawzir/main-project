<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PasswordResets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            // $table->dropPrimary('id');
            $table->dropIndex('idx_tcp_password_resets__user_id');
            $table->dropColumn('id');
            $table->dropColumn('regen_code');
            $table->dropColumn('regen_expiry');
            $table->dropColumn('user_id');
            $table->string('email');
            $table->string('token');
            $table->timestamps();
            $table->dropColumn('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('token');
            $table->dropColumn('email');
            $table->unsignedInteger('user_id')->length(10);
            $table->integer('regen_expiry')->length(11);
            $table->char('regen_code', 32)->default('');
            $table->unsignedInteger('id', true)->length(10);
            $table->index('user_id', 'idx_tcp_password_resets__user_id');
        });
    }
}
