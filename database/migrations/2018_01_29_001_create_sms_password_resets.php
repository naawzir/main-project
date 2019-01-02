<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsPasswordResets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('sms_password_resets')) return;
        Schema::create('sms_password_resets', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('mobile', 20);
            $table->string('token', 100);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_password_resets');
    }
}
