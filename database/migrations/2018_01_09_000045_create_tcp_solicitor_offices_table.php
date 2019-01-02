<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpSolicitorOfficesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'solicitor_offices';
    /**
     * Run the migrations.
     * @table tcp_solicitor_offices
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
            $table->unsignedInteger('address_id')->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->string('phone', 20)->nullable()->default(null);
            $table->integer('active')->nullable()->default('1');
            $table->string('reminder_email_address')->nullable()->default(null);
            $table->string('tm_ref', 32)->nullable()->default(null);
            $table->integer('capacity')->nullable()->default(null)->comment('This used to be pipeline_target');
            $table->unsignedInteger('date_created');
            $table->unsignedInteger('date_updated');

            $table->index(["solicitor_id"], 'idx_tcp_solicitor_offices__solicitor_id');
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

