<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpInstructionDocumentsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'instruction_documents';
    /**
     * Run the migrations.
     * @table tcp_instruction_documents
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('instruction_id');
            $table->unsignedInteger('tm_document_id');
            $table->unsignedInteger('date_created');
            $table->unsignedInteger('date_uploaded')->nullable()->default(null);
            $table->string('status', 20)->nullable()->default(null);
            $table->unsignedTinyInteger('version');
            $table->string('file_name', 200);
            $table->string('file_type', 50);
            $table->string('document_type', 50);
            $table->string('document_description', 200);

            $table->index(["instruction_id"], 'FK_tcp_instruction_documents_tcp_instructions');

            $table->index(["version"], 'idx_tcp_instruction_documents__version');

            $table->index(["status"], 'idx_tcp_instruction_documents__status');

            $table->unique(["document_id", "instruction_id"], 'document_id_instruction_id');


            $table->foreign('document_id', 'document_id_instruction_id')
                ->references('id')->on('documents')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('instruction_id', 'FK_tcp_instruction_documents_tcp_instructions')
                ->references('id')->on('instructions')
                ->onDelete('restrict')
                ->onUpdate('cascade');
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
