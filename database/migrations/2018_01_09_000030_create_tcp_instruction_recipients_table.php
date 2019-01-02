<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpInstructionRecipientsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'instruction_recipients';
    /**
     * Run the migrations.
     * @table tcp_instruction_recipients
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('instruction_id');
            $table->unsignedMediumInteger('tm_id');
            $table->string('name', 200);
            $table->string('message_party', 200);
            $table->string('party_type_description', 200);
            $table->string('delivery_types', 200);

            $table->index(["tm_id"], 'instruction_id_tm_id');

            $table->index(["instruction_id"], 'FK_tcp_instruction_recipients_tcp_instructions');


            $table->foreign('instruction_id', 'FK_tcp_instruction_recipients_tcp_instructions')
                ->references('id')->on('instructions')
                ->onDelete('cascade')
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
