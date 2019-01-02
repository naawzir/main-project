<?php

use Illuminate\Database\Migrations\Migration;
use App\Solicitor;
use App\Disbursement;
use Illuminate\Support\Facades\Schema;

class createSolicitorDisbursementsTcpDisbursements extends Migration
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
        Schema::table('disbursements', function(\Illuminate\Database\Schema\Blueprint $table) {
            $table->unsignedInteger('solicitor_id')->nullable()->default(null);
            $table->foreign('solicitor_id', 'FK_disbursements_solicitors')
                ->references('id')->on('solicitors')
                ->onDelete('set null')
                ->onUpdate('set null');
        });

        $solicitorModel = new Solicitor;
        $solicitors = $solicitorModel->all();
        $solicitorsIds = [];

        foreach($solicitors as $solicitor) {

            $solicitorsIds[] = $solicitor->id;

        }

    }

}
