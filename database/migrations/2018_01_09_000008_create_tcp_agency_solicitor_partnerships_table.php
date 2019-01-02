<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpAgencySolicitorPartnershipsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'agency_solicitor_partnerships';
    /**
     * Run the migrations.
     * @table tcp_agency_solicitor_partnerships
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('agency_id')->nullable()->default(null);
            $table->unsignedInteger('solicitor_id')->nullable()->default(null);

            $table->index(["solicitor_id"], 'idx_tcp_agency_solicitor_partnerships__solicitor_id');

            $table->index(["agency_id"], 'idx_tcp_agency_solicitor_partnerships__agency_id');
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
