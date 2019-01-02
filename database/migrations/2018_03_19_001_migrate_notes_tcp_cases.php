<?php

use Illuminate\Database\Migrations\Migration;

class MigrateNotesTcpCases extends Migration
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
        $query = "insert into notes(
            target_id,
            target,
            target_type,
            user_id,
            note_content,
            notified,
            date_created
        )
        
        select 
            id,
            'case',
            'first-agent-note',
            user_id_agent,
            notes,
            0,
            UNIX_TIMESTAMP()
        FROM cases
        WHERE notes !=''
        and notes is not null
        order by id asc;";

        DB::statement($query);

    }

}
