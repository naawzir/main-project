<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpSolicitorScoresTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'solicitor_scores';
    /**
     * Run the migrations.
     * @table tcp_solicitor_scores
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id_agent');
            $table->unsignedInteger('solicitor_id');
            $table->string('score', 3)->nullable()->default(null);
            $table->unsignedInteger('date_created');

            $table->index(["user_id_agent"], 'idx_tcp_solicitor_scores__user_id_agent');

            $table->index(["solicitor_id"], 'idx_tcp_solicitor_scores__solicitor_id');
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

