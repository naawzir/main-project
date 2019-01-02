<?php

use Illuminate\Database\Migrations\Migration;

class UserIdUpdateTcpNotes extends Migration
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

        $query = "update notes AS `n`
            inner join ids as `ids` on ids.target_id = n.user_id
            set n.user_id = ids.id
            where ids.target_type = 'User';";

        DB::statement($query);

    }

}
