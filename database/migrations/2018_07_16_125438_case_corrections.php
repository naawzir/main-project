<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CaseCorrections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->unsignedInteger('date_completed')->length(10)->default(null)->change();
            $table->unsignedInteger('date_archived')->length(10)->default(null)->change();
            $table->unsignedInteger('date_prospect')->length(10)->default(null)->change();
            $table->longText('notes')->change();
            $table->longText('notes_tcp')->change();
            $table->boolean('active')->default(1)->change();
            $table->unsignedSmallInteger('milestone')->length(3)->default(null)->change();
            $table->unsignedInteger('milestone_updated_date')->length(11)->default(null)->change();
            $table->boolean('src_required')->default(0)->change();
            $table->unsignedInteger('date_instructed')->length(10)->default(null)->change();
            $table->unsignedInteger('date_declined')->length(10)->default(null)->change();
            $table->boolean('panelled')->default(1)->change();
            $table->unsignedInteger('date_unpanelled')->length(10)->default(null)->change();
            $table->unsignedInteger('date_on_hold')->length(10)->default(null)->change();
            $table->unsignedInteger('date_aborted')->length(10)->default(null)->change();
            $table->mediumText('archive_reason')->change();
            $table->boolean('mortgage_new')->default(0)->change();
            $table->boolean('mortgage_redeem')->default(0)->change();
            $table->boolean('alerts')->default(1)->change();
            $table->unsignedInteger('date_exchanged')->length(10)->default(null)->change();
            $table->boolean('mortgage')->default(0)->change();
            $table->boolean('offer')->default(0)->change();
            $table->boolean('new')->default(0)->change();
            $table->unsignedInteger('date_active')->length(10)->default(null)->change();
            $table->boolean('second_home')->default(0)->change();
            $table->decimal('discount', 13, 2)->default(null)->change();
            $table->unsignedInteger('contact_date')->length(10)->default(null)->change();
            $table->boolean('aml_fees_paid')->default(0)->comment('Set to 1 when all AML fees been paid, this will affect commission')->change();
            $table->boolean('all_aml_searches_complete')->default(0)->comment('Set to 1 when all AML searches have been done')->change();
            $table->unsignedInteger('date_last_contacted')->length(10)->default(null)->change();
            $table->unsignedInteger('date_created')->length(10)->change();
            $table->unsignedInteger('date_updated')->length(10)->change();
            $table->unsignedInteger('date_aml_fees_paid')->length(10)->default(null)->change();

            $table->dropIndex('status');
            $table->index('status','idx_tcp_cases__status');

            $table->dropIndex('reference');
            $table->index('reference','idx_tcp_cases__reference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cases', function (Blueprint $table) {

            $table->dropIndex('idx_tcp_cases__reference');
            $table->index('reference');

            $table->dropIndex('idx_tcp_cases__status');
            $table->index('status');

            $table->integer('date_aml_fees_paid')->length(10)->default(NULL)->change();
            $table->unsignedInteger('date_updated')->length(10)->change();
            $table->unsignedInteger('date_created')->length(10)->change();
            $table->unsignedInteger('date_last_contacted')->length(10)->default(NULL)->change();
            $table->unsignedTinyInteger('all_aml_searches_complete')->length(3)->default(null)->comment('Set to 1 when all AML searches have been done')->change();
            $table->unsignedTinyInteger('aml_fees_paid')->length(3)->default(null)->comment('Set to 1 when all AML fees been paid, this will affect commission')->change();
            $table->integer('contact_date')->length(11)->default(NULL)->change();
            $table->double('discount', 8, 2)->default(null)->change();
            $table->integer('second_home')->length(10)->default(null)->change();
            $table->unsignedInteger('date_active')->length(10)->default(NULL)->change();
            $table->integer('new')->length(10)->default(null)->change();
            $table->integer('offer')->length(10)->default(null)->change();
            $table->integer('mortgage')->length(10)->default(null)->change();
            $table->unsignedInteger('date_exchanged')->length(10)->default(NULL)->change();
            $table->integer('alerts')->length(10)->default(1)->change();
            $table->integer('mortgage_redeem')->length(10)->default(0)->change();
            $table->integer('mortgage_new')->length(10)->default(0)->change();
            $table->text('archive_reason')->change();
            $table->unsignedInteger('date_aborted')->length(11)->default(0)->change();
            $table->unsignedInteger('date_on_hold')->length(10)->default(null)->change();
            $table->unsignedInteger('date_unpanelled')->length(11)->default(0)->change();
            $table->integer('panelled')->length(11)->default(1)->change();
            $table->unsignedInteger('date_declined')->length(11)->default(0)->change();
            $table->unsignedInteger('date_instructed')->length(11)->default(0)->change();
            $table->integer('src_required')->length(11)->default(null)->change();
            $table->integer('milestone_updated_date')->length(10)->default(0)->change();
            $table->integer('milestone')->length(11)->default(0)->change();
            $table->integer('active')->length(11)->default(1)->change();
            $table->mediumText('notes_tcp')->change();
            $table->mediumText('notes')->change();
            $table->unsignedInteger('date_prospect')->length(11)->default(null)->change();
            $table->unsignedInteger('date_archived')->length(11)->default(0)->change();
            $table->unsignedInteger('date_completed')->length(11)->default(0)->change();
        });
    }
}
