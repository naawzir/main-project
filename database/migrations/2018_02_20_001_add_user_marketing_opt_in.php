<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddUserMarketingOptIn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'sms_marketing_opt_in')) return;
        if (Schema::hasColumn('users', 'email_marketing_opt_in')) return;

        Schema::table('users', function ($table) {

            $table->boolean('sms_marketing_opt_in')->unsigned()->default(0);
            $table->boolean('email_marketing_opt_in')->unsigned()->default(0);

        });
    }
}