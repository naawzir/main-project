<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpTasksTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'tasks';
    /**
     * Run the migrations.
     * @table tcp_tasks
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 128)->nullable()->default(null);
            $table->unsignedInteger('target_id');
            $table->string('target_type', 128)->nullable()->default(null);
            $table->string('type', 20)->default('');
            $table->text('notes')->nullable()->default(null);
            $table->integer('follow_up')->nullable()->default(null)->comment('0 => not required,\\n1 => required,\\n2 => required+completed');
            $table->text('data')->nullable()->default(null);
            $table->unsignedInteger('user_id_staff_completed')->nullable()->default(null);
            $table->unsignedInteger('date_completed')->nullable()->default(null);
            $table->unsignedInteger('date_time')->default('0');
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');

            $table->index(["target_id"], 'idx_tcp_tasks__target_id');
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

