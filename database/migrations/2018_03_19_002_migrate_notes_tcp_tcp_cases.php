<?php

use Illuminate\Database\Migrations\Migration;

class MigrateNotesTcpTcpCases extends Migration
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
            'instruction-solicitor',
            user_id_staff,
            notes_tcp,
            1,
            UNIX_TIMESTAMP()
        FROM cases
        WHERE notes_tcp !=''
        and notes_tcp is not null
        order by id asc;";

        DB::statement($query);

    }

}
