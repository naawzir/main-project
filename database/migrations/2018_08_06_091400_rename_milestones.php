<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMilestones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('transaction_milestones') && Schema::hasTable('milestones') && Schema::hasTable('tm_milestones')) {

            Schema::rename('milestones', 'transaction_milestones');
            Schema::rename('tm_milestones', 'milestones');

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('tm_milestones') && Schema::hasTable('transaction_milestones')) {

            Schema::rename('milestones', 'tm_milestones');
            Schema::rename('transaction_milestones', 'milestones');

        }

    }

}