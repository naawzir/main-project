<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpNotesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'notes';
    /**
     * Run the migrations.
     * @table tcp_notes
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('target_id')->nullable()->default(null);
            $table->string('target', 50)->nullable()->default(null);
            $table->string('target_type', 50)->default('');
            $table->string('subtype', 50)->nullable()->default(null);
            $table->char('user_id', 32)->nullable()->default(null);
            $table->text('data')->nullable()->default(null);
            $table->text('note_content')->nullable()->default(null);
            $table->unsignedInteger('notified')->nullable()->default('0');
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');

            $table->index(["target_type"], 'idx_tcp_notes__target_type');

            $table->index(["target_id", "target"], 'idx_tcp_notes__target_id_target');
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

