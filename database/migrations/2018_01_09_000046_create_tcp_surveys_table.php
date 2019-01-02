<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpSurveysTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'surveys';
    /**
     * Run the migrations.
     * @table tcp_surveys
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
            $table->integer('q1_score')->default('0');
            $table->integer('q2_score')->default('0');
            $table->string('q1_comments')->default('');
            $table->string('q2_comments')->default('');
            $table->string('comments')->default('');
            $table->integer('date_completed')->nullable()->default(null);
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');

            $table->index(["user_id_agent"], 'idx_tcp_surveys__user_id_agent');
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

