<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpInstructionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'instructions';
    /**
     * Run the migrations.
     * @table tcp_instructions
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('case_id')->nullable()->default(null);
            $table->string('status', 45)->nullable()->default(null);
            $table->string('reference', 32)->nullable()->default(null);
            $table->string('case_type', 12);
            $table->integer('date_sent')->nullable()->default('0');
            $table->char('case_id_linked_to', 32)->nullable()->default(null);
            $table->unsignedInteger('panelled')->default('1');
            $table->string('tm_reference', 50)->nullable()->default(null);
            $table->unsignedTinyInteger('tm_reference_tries')->default('0');
            $table->unsignedInteger('date_tm_recipients_last_fetched')->nullable()->default(null);
            $table->unsignedTinyInteger('tm_recipients_fetch_auto_tries')->default('0');
            $table->unsignedInteger('date_created');
            $table->unsignedInteger('date_updated');

            $table->index(["case_id_linked_to"], 'idx_tcp_instructions__case_id_linked_to');

            $table->index(["case_id"], 'idx_tcp_instructions__case_id');


            $table->foreign('case_id', 'idx_tcp_instructions__case_id')
                ->references('id')->on('cases')
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
