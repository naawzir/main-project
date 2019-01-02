<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTransactionMilestones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_milestones', function (Blueprint $table) {
            $table->renameColumn('case_id', 'transaction_id');
            $table->renameColumn('milestone','milestone_id');
            $table->dropColumn('case_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_milestones', function (Blueprint $table) {
            $table->string('case_type', 45)->nullable()->default(null);
            $table->renameColumn('milestone_id','milestone');
            $table->renameColumn('transaction_id','case_id');
        });
    }
}
