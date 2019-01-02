<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpDisbursementsSolicitorsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'disbursements_solicitors';
    /**
     * Run the migrations.
     * @table tcp_disbursements_solicitors
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('solicitor_id')->nullable()->default(null);
            $table->unsignedInteger('disbursement_id')->nullable()->default(null);
            $table->float('cost')->nullable()->default('0');
            $table->integer('active')->nullable()->default('1');
            $table->string('type', 20)->nullable()->default('buyer');

            $table->index(["disbursement_id"], 'idx_tcp_disbursements_solicitors__disbursement_id');

            $table->index(["solicitor_id"], 'idx_tcp_disbursements_solicitors__solicitor_id');
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
