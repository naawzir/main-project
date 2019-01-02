<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TcpUsersMigrateSolicitors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = "INSERT INTO users (
            active,
            date_created,
            date_updated,
            title,
            forenames,
            surname,
            email,
            password,
            phone,
            phone_other,
            sms_marketing_opt_in,
            email_marketing_opt_in,
            user_role_id
        )
        
        SELECT 
            active,
            UNIX_TIMESTAMP(),
            UNIX_TIMESTAMP(),
            title,
            forenames,
            surname,
            email,
            '$2y$10\$lsOk3e3u6.jeA1nLpwb8iuNLNWGbncdgvLtskLYg1lsxaOvRk/qTu',
            phone,
            phone_other,
            0,
            0,
            11
        FROM solicitor_users";
        DB::statement($query);

    }

    public function down()
    {
        $query = "DELETE FROM users WHERE user_role_id = 11;";
        DB::statement($query);
    }

}
