<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameReminderEmailAddressSolicitorOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('solicitor_offices', 'email')) return;
        Schema::table('solicitor_offices', function(Blueprint $table) {
            $table->renameColumn('reminder_email_address', 'email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('solicitor_offices', 'reminder_email_address')) return;
        Schema::table('solicitor_offices', function(Blueprint $table) {
            $table->renameColumn('email', 'reminder_email_address');
        });
    }
}