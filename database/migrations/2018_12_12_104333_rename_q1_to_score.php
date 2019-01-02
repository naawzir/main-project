<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameQ1ToScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('feedback_agents_for_solicitor_offices') && Schema::hasColumn('feedback_agents_for_solicitor_offices', 'q1')) {
            Schema::table('feedback_agents_for_solicitor_offices', function (Blueprint $table) {
                $table->renameColumn('q1', 'score');
            });
        }

        if(Schema::hasTable('feedback_agents_for_solicitor_offices') && Schema::hasColumn('feedback_agents_for_solicitor_offices', 'q1_score')) {
            Schema::table('feedback_agents_for_solicitor_offices', function (Blueprint $table) {
                $table->renameColumn('q1_score', 'score');
            });
        }

        if(Schema::hasTable('feedback_agents_for_tcp') && Schema::hasColumn('feedback_agents_for_tcp', 'q1')) {
            Schema::table('feedback_agents_for_tcp', function (Blueprint $table) {
                $table->renameColumn('q1', 'score');
            });
        }

        if(Schema::hasTable('feedback_agents_for_tcp') && Schema::hasColumn('feedback_agents_for_tcp', 'q1_score')) {
            Schema::table('feedback_agents_for_tcp', function (Blueprint $table) {
                $table->renameColumn('q1_score', 'score');
            });
        }

        if(Schema::hasTable('feedback_customers_for_solicitor_offices') && Schema::hasColumn('feedback_customers_for_solicitor_offices', 'q1')) {
            Schema::table('feedback_customers_for_solicitor_offices', function (Blueprint $table) {
                $table->renameColumn('q1', 'score');
            });
        }

        if(Schema::hasTable('feedback_customers_for_solicitor_offices') && Schema::hasColumn('feedback_customers_for_solicitor_offices', 'q1_score')) {
            Schema::table('feedback_customers_for_solicitor_offices', function (Blueprint $table) {
                $table->renameColumn('q1_score', 'score');
            });
        }

        if(Schema::hasTable('feedback_customers_for_tcp') && Schema::hasColumn('feedback_customers_for_tcp', 'q1')) {
            Schema::table('feedback_customers_for_tcp', function (Blueprint $table) {
                $table->renameColumn('q1', 'score');
            });
        }

        if(Schema::hasTable('feedback_customers_for_tcp') && Schema::hasColumn('feedback_customers_for_tcp', 'q1_score')) {
            Schema::table('feedback_customers_for_tcp', function (Blueprint $table) {
                $table->renameColumn('q1_score', 'score');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('feedback_agents_for_solicitor_offices') && Schema::hasColumn('feedback_agents_for_solicitor_offices', 'score')) {
            Schema::table('feedback_agents_for_solicitor_offices', function (Blueprint $table) {
                $table->renameColumn('score', 'q1');
            });
        }

        if(Schema::hasTable('feedback_agents_for_tcp') && Schema::hasColumn('feedback_agents_for_tcp', 'score')) {
            Schema::table('feedback_agents_for_tcp', function (Blueprint $table) {
                $table->renameColumn('score', 'q1');
            });
        }

        if(Schema::hasTable('feedback_customers_for_solicitor_offices') && Schema::hasColumn('feedback_customers_for_solicitor_offices', 'score')) {
            Schema::table('feedback_customers_for_solicitor_offices', function (Blueprint $table) {
                $table->renameColumn('score', 'q1');
            });
        }

        if(Schema::hasTable('feedback_customers_for_tcp') && Schema::hasColumn('feedback_customers_for_tcp', 'score')) {
            Schema::table('feedback_customers_for_tcp', function (Blueprint $table) {
                $table->renameColumn('score', 'q1');
            });
        }
    }
}
