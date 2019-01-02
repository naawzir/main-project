<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpPropertyReportsDataTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'property_reports_data';
    /**
     * Run the migrations.
     * @table ias_compliance_data
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('property_report_id')->nullable()->default(null);
            $table->unsignedInteger('property_report_section_id')->nullable()->default(null);
            $table->text('data')->nullable()->default(null);

            $table->index(["property_report_id"], 'FK_tcp_property_reports_data__property_report_id');

            $table->index(["property_report_section_id"], 'FK_tcp_property_reports_data__property_report_section_id');

            $table->index(["property_report_id"], 'idx_tcp_property_reports_data__property_report_id');

            $table->index(["property_report_section_id"], 'idx_tcp_property_reports_data__property_report_section_id');


            $table->foreign('property_report_id', 'FK_tcp_property_reports_data__property_report_id')
                ->references('id')->on('tcp_property_reports')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('property_report_section_id', 'FK_tcp_property_reports_data__property_report_section_id')
                ->references('id')->on('tcp_property_reports_sections')
                ->onDelete('restrict')
                ->onUpdate('cascade');
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

