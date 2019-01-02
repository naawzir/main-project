<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpPropertyReportsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'property_reports';
    /**
     * Run the migrations.
     * @table tcp_property_reports
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('address_id')->nullable()->default(null);
            $table->unsignedInteger('agency_id')->nullable()->default(null);
            $table->unsignedInteger('agency_branch_id')->nullable()->default(null);
            $table->unsignedInteger('user_id_agent')->nullable()->default(null);
            $table->unsignedInteger('active')->default('1');
            $table->string('reference', 20)->nullable()->default(null);
            $table->unsignedInteger('user_id_customer')->nullable()->default(null);
            $table->unsignedInteger('user_id_customer2')->nullable()->default(null);
            $table->unsignedInteger('case_id')->nullable()->default(null);
            $table->text('completion_sections_pct')->nullable()->default(null);
            $table->string('status', 50);
            $table->string('slug');
            $table->text('esign')->nullable()->default(null);
            $table->unsignedInteger('user_id_customer_completion')->nullable()->default(null);
            $table->unsignedInteger('document_id')->nullable()->default(null);
            $table->unsignedInteger('compiled_document_id')->nullable()->default(null);
            $table->integer('terms_accepted')->nullable()->default('0');
            $table->integer('privacy_policy_accepted')->nullable()->default('0');
            $table->string('conveyancing_email', 50)->nullable()->default(null);
            $table->integer('date_completed')->nullable()->default(null);
            $table->integer('date_created')->nullable()->default(null);
            $table->integer('date_updated')->nullable()->default(null);

            $table->index(["compiled_document_id"], 'FK_tcp_property_reports_tcp_documents_2');

            $table->index(["agency_branch_id"], 'idx_tcp_property_reports__agency_branch_id');

            $table->index(["user_id_agent"], 'idx_tcp_property_reports__user_id_agent');

            $table->index(["case_id"], 'FK__tcp_cases');

            $table->index(["document_id"], 'FK_tcp_property_reports_tcp_documents');

            $table->index(["user_id_customer"], 'idx_tcp_property_reports__user_customer_id');

            $table->index(["address_id"], 'idx_tcp_property_reports__address_id');

            $table->index(["agency_id"], 'idx_tcp_property_reports__agency_id');

            $table->unique(["slug"], 'slug');


            $table->foreign('case_id', 'FK__tcp_cases')
                ->references('id')->on('cases')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('document_id', 'FK_tcp_property_reports_tcp_documents')
                ->references('id')->on('documents')
                ->onDelete('set null')
                ->onUpdate('set null');

            $table->foreign('compiled_document_id', 'FK_tcp_property_reports_tcp_documents_2')
                ->references('id')->on('documents')
                ->onDelete('restrict')
                ->onUpdate('restrict');
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

