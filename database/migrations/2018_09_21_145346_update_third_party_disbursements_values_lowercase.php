<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateThirdPartyDisbursementsValuesLowercase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("update third_party_disbursements set `transaction` = 'sale' where `transaction` = 'Sale';");
        DB::statement("update third_party_disbursements set `transaction` = 'purchase' where `transaction` = 'Purchase';");
        DB::statement("update third_party_disbursements set `type` = 'case' where `type` = 'Case';");
        DB::statement("update third_party_disbursements set `type` = 'client' where `type` = 'Client(s)';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("update third_party_disbursements set `transaction` = 'Sale' where `transaction` = 'sale';");
        DB::statement("update third_party_disbursements set `transaction` = 'Purchase' where `transaction` = 'purchase';");
        DB::statement("update third_party_disbursements set `type` = 'Case' where `type` = 'case';");
        DB::statement("update third_party_disbursements set `type` = 'Client(s)' where `type` = 'client';");
    }
}
