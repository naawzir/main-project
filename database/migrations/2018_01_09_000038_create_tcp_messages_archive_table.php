<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpMessagesArchiveTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'messages_archive';
    /**
     * Run the migrations.
     * @table tcp_messages_archive
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('sender_id', 32)->default('');
            $table->char('user_id_sender', 32)->nullable()->default('');
            $table->unsignedInteger('associated_id')->nullable()->default(null);
            $table->string('associated_target', 150)->nullable()->default('');
            $table->char('recipient_id', 32)->nullable()->default('');
            $table->string('sender_email', 128)->default('');
            $table->text('recipient_email');
            $table->string('subject', 250)->default('');
            $table->text('body_html')->nullable()->default(null);
            $table->text('body_text')->nullable()->default(null);
            $table->integer('send_attempts')->default('0');
            $table->text('send_log')->nullable()->default(null);
            $table->text('attachments')->nullable()->default(null);
            $table->string('reply_to_address', 128)->nullable()->default(null);
            $table->text('cc_email')->nullable()->default(null);
            $table->text('bcc_email')->nullable()->default(null);
            $table->text('attach_filepaths')->nullable()->default(null);
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');
            $table->unsignedInteger('date_sent')->default('0');

            $table->index(["date_sent"], 'idx_tcp_messages_archive__date_sent');

            $table->index(["associated_id"], 'idx_tcp_messages_archive__associated_id');
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

