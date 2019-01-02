<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpUserAgentsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'user_agents';
    /**
     * Run the migrations.
     * @table tcp_user_agents
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('agency_id');
            $table->unsignedInteger('agency_branch_id');
            $table->unsignedInteger('user_id');
            $table->integer('points')->default('0');
            $table->integer('points_wallet')->default('0');
            $table->unsignedInteger('valuer')->default('0');
            $table->unsignedInteger('user_id_staff')->nullable()->default(null);
            $table->unsignedInteger('registered_for_points')->nullable()->default('0');
            $table->unsignedInteger('show_survey')->nullable()->default('1');
            $table->string('position', 45)->nullable()->default(null);
            $table->string('rewards_email', 50);

            $table->index(["agency_id"], 'idx_tcp_user_agents__agency_id');

            $table->index(["user_id_staff"], 'idx_tcp_user_agents__user_id_staff');

            $table->index(["agency_branch_id"], 'idx_tcp_user_agents__agency_branch_id');

            $table->index(["user_id"], 'idx_tcp_user_agents__user_id');

            $table->unique(["user_id"], 'idx_user_agent_user_id');
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

