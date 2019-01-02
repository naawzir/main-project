<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ConveyancingCasesHistory;
use App\ConveyancingCase;
use App\Cases;
use App\Transaction;

class PopulateConveyancingCasesHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*        $fields = [
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
            'new',
            'related_case_id',
            'user_id_agent',
            'panelled',
            'referrer_name',
        ];

        $cases = Cases::all();*/

/*        foreach ($cases as $case) {
            $attributes = $case->attributesToArray();
            $newArray = [];
            foreach ($attributes as $field => $value) {
                if (in_array($field, $fields)) {
                    $newArray[$field] = $value;
                }
            }
            $conveyCasesHistory = new ConveyancingCasesHistory;
            $conveyCasesHistory->conveyancing_case_id = $case->id;
            $conveyCasesHistory->data = json_encode($newArray);
            $conveyCasesHistory->save();
        }*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('conveyancing_cases_history')->truncate();
    }
}
