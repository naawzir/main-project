<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpDocumentsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'documents';
    /**
     * Run the migrations.
     * @table tcp_documents
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('file_name', 100)->default('');
            $table->string('file_type', 25)->default('');
            $table->string('title', 100)->default('');
            $table->tinyInteger('level')->default('0');
            $table->text('tags')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
            $table->unsignedInteger('target_id');
            $table->string('target_type', 50)->nullable()->default(null);
            $table->string('request_code', 30)->nullable()->default(null);
            $table->unsignedInteger('do_regenerate')->default('0')->comment('This will be set to "1" so that any automaton generation process knows to regenerate it.');
            $table->text('link')->nullable()->default(null);
            $table->integer('cf_upload')->nullable()->default('0');
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');

            $table->index(["target_id"], 'idx_target_id');
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
