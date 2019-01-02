<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpMilestonesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'milestones';
    /**
     * Run the migrations.
     * @table tcp_milestones
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
            $table->string('milestone', 50)->default('');
            $table->integer('date_due')->default('0');
            $table->string('case_type', 45)->nullable()->default(null);
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');

            $table->index(["case_id"], 'idx_tcp_milestones__case_id');

            $table->index(["milestone"], 'idx_tcp_milestones__milestone');
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

