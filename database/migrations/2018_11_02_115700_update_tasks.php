<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE tasks
                            SET type = 
                                CASE type 
                                    WHEN 'Solicitor Office Contact' THEN 'solicitor-office-contact'
                                    WHEN 'Complaint Follow Up' THEN 'complaint-follow-up'
                                    WHEN 'New Solicitor Office' THEN 'new-solicitor-office'
                                    WHEN 'Chase TM Set Up' THEN 'chase-tm-set-up'
                                    WHEN 'Panel Manager Audit' THEN 'panel-manager-audit'
                                    ELSE type
                                    END;");

        DB::statement("UPDATE tasks
                            SET target_type = 'SolicitorOffice'
                                WHERE target_type = 'Solicitor Office';");
    }
}
