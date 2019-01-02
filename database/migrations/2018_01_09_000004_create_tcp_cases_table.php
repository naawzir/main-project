<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpCasesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'cases';
    /**
     * Run the migrations.
     * @table tcp_cases
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('related_case_id')->nullable()->default(null);
            $table->unsignedInteger('address_id')->nullable()->default(null);
            $table->unsignedInteger('agency_id')->nullable()->default(null);
            $table->unsignedInteger('agency_branch_id')->nullable()->default(null);
            $table->unsignedInteger('user_id_agent');
            $table->unsignedInteger('fee_structure_id')->nullable()->default(null);
            $table->unsignedInteger('user_id_staff')->nullable()->default(null);
            $table->unsignedInteger('solicitor_id')->nullable()->default(null);
            $table->unsignedInteger('solicitor_office_id')->nullable()->default(null);
            $table->unsignedInteger('solicitor_user_id')->nullable()->default(null);
            $table->string('reference', 20)->nullable()->default(null);
            $table->string('status', 10)->nullable()->default(null);
            $table->integer('date_completed')->nullable()->default('0');
            $table->integer('date_archived')->nullable()->default('0');
            $table->unsignedInteger('date_prospect')->nullable()->default(null);
            $table->mediumText('notes')->nullable()->default(null);
            $table->mediumText('notes_tcp')->nullable()->default(null);
            $table->integer('active')->nullable()->default('1');
            $table->integer('milestone')->nullable()->default('0');
            $table->integer('milestone_updated_date')->nullable()->default('0');
            $table->integer('src_required')->nullable()->default(null);
            $table->string('type', 24)->nullable()->default(null);
            $table->integer('date_instructed')->nullable()->default('0');
            $table->integer('date_declined')->nullable()->default('0');
            $table->integer('panelled')->nullable()->default('1');
            $table->integer('date_unpanelled')->nullable()->default('0');
            $table->string('source', 45)->nullable()->default(null);
            $table->string('referrer_name', 128)->nullable()->default(null);
            $table->integer('date_abeyance')->nullable()->default(null);
            $table->unsignedInteger('date_on_hold')->nullable()->default(null);
            $table->integer('date_aborted')->nullable()->default(null);
            $table->text('archive_reason')->nullable()->default(null);
            $table->string('lead_source', 30)->default('none');
            $table->unsignedInteger('duplicate_of_id')->nullable()->default(null);
            $table->unsignedInteger('mortgage_new')->nullable()->default('0');
            $table->unsignedInteger('mortgage_redeem')->nullable()->default('0');
            $table->unsignedInteger('alerts')->nullable()->default('1');
            $table->unsignedInteger('date_exchanged')->nullable()->default(null);
            $table->float('price')->nullable()->default(null);
            $table->string('tenure', 128)->nullable()->default(null);
            $table->integer('mortgage')->nullable()->default(null);
            $table->integer('offer')->nullable()->default(null);
            $table->integer('new')->nullable()->default(null);
            $table->unsignedInteger('date_active')->nullable()->default(null);
            $table->integer('second_home')->nullable()->default(null);
            $table->float('discount')->nullable()->default(null);
            $table->integer('contact_date')->nullable()->default(null);
            $table->string('contact_time', 45)->nullable()->default(null);
            $table->unsignedTinyInteger('aml_fees_paid')->nullable()->default(null)->comment('Set to 1 when all AML fees been paid, this will affect commission');
            $table->unsignedTinyInteger('all_aml_searches_complete')->nullable()->default(null)->comment('Set to 1 when all AML searches have been done');
            $table->unsignedInteger('date_last_contacted')->nullable()->default(null);
            $table->string('abort_code', 25)->nullable()->default(null);
            $table->unsignedInteger('date_created');
            $table->unsignedInteger('date_updated');

            $table->index(["status"], 'status');

            $table->index(["address_id"], 'idx_tcp_cases__address_id');

            $table->index(["reference"], 'reference');

            $table->index(["agency_branch_id"], 'idx_tcp_cases__agency_branch_id');

            $table->index(["solicitor_id"], 'idx_tcp_cases__solicitor_id');

            $table->index(["duplicate_of_id"], 'idx_tcp_cases__duplicate_of_id');

            $table->index(["user_id_agent"], 'idx_tcp_cases__user_id_agent');

            $table->index(["solicitor_office_id"], 'idx_tcp_cases__solicitor_office_id');

            $table->index(["agency_id"], 'idx_tcp_cases__agency_id');

            $table->index(["user_id_staff"], 'idx_tcp_cases__user_id_staff');

            $table->index(["fee_structure_id"], 'idx_tcp_cases__fee_structures_id');

            $table->index(["solicitor_user_id"], 'idx_tcp_cases__solicitor_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->set_schema_table);
     }
}
