<?php

use Illuminate\Database\Migrations\Migration;

class UserAgentsFix extends Migration
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

        $query = "update users AS `u`
        inner join cases as `c` on c.user_id_agent = u.id
        set u.user_role_id = 9
        where u.user_role_id = 0;";

        DB::statement($query);

        $query = "insert into user_agents (
            user_id,
            agency_id, 
            agency_branch_id
        ) 
        select 
            u.id,
            c.agency_id,
            c.agency_branch_id
        FROM cases as `c`
        right join users as `u` on u.id = c.user_id_agent
        left join user_agents as `ua` on ua.user_id = u.id
        where 
            u.user_role_id = 9
        and ua.id is null
        group by u.id;";

        DB::statement($query);

    }

}
