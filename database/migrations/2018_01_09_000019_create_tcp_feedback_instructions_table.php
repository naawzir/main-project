<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpFeedbackInstructionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'feedback_instructions';
    /**
     * Run the migrations.
     * @table tcp_feedback_instructions
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('case_id');
            $table->char('user_id_customer', 32)->nullable()->default(null);
            $table->string('q1', 10)->nullable()->default(null);
            $table->char('check_string', 32)->nullable()->default(null);
            $table->unsignedInteger('date_created');
            $table->unsignedInteger('date_updated');

            $table->index(["user_id_customer"], 'idx_tcp_feedback_instructions__user_id_customer');

            $table->index(["case_id"], 'idx_tcp_feedback_instructions__case_id');
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
