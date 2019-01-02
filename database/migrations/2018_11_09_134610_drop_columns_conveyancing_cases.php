<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsConveyancingCases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $fields = [
            'abort_code',
            'address_id',
            'agency_branch_id',
            'agency_id',
            'archive_reason',
            'contact_date',
            'contact_time',
            'date_abeyance',
            'date_aborted',
            'date_active',
            'date_archived',
            'date_completed',
            'date_declined',
            //'date_exchanged',
            'date_instructed',
            'date_last_contacted',
            'date_on_hold',
            'date_prospect',
            'date_unpanelled',
            'duplicate_of_id',
            'milestone',
            'milestone_updated_date',
            //'new',
            //'related_case_id',
            'user_id_agent',
            'panelled',
            'referrer_name',
            'user_id_staff'
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('conveyancing_cases', function (Blueprint $table) use ($fields) {
            foreach ($fields as $field) {
                if (in_array($field, $fields)) {
                    if (Schema::hasColumn('conveyancing_cases', $field)) {
                        $table->dropColumn($field);
                    }
                }
            }
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
