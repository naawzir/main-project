<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpPropertyReportTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'property_report';
    /**
     * Run the migrations.
     * @table tcp_property_report
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('datecreated')->nullable()->default(null);
            $table->unsignedInteger('address_id')->nullable()->default(null);
            $table->char('agency_id', 32)->nullable()->default(null);
            $table->char('agency_branch_id', 32)->nullable()->default(null);
            $table->char('user_agent_staff_id', 32)->nullable()->default(null);
            $table->char('user_staff_id', 32)->nullable()->default(null);
            $table->integer('isarchived')->nullable()->default('0');
            $table->string('reference', 20)->nullable()->default(null);
            $table->char('user_customer_id', 32)->nullable()->default(null);
            $table->char('user_customer2_id', 32)->nullable()->default(null);
            $table->char('transaction_address_id_backup', 32)->nullable()->default(null);
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

