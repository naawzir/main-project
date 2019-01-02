<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUserIdCustomerFeedbackCompletions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('feedback_completions', 'customer_id')) return;

        Schema::table('feedback_completions', function ($table) {

            $table->renameColumn('user_id_customer', 'customer_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('feedback_completions', 'user_id_customer')) return;

        Schema::table('feedback_completions', function ($table) {

            $table->renameColumn('customer_id', 'user_id_customer');

        });
    }
}
