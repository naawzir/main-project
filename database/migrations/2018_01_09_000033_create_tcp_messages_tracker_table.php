<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpMessagesTrackerTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'messages_tracker';
    /**
     * Run the migrations.
     * @table tcp_messages_tracker
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('message_id');
            $table->string('subject_line')->nullable()->default(null);
            $table->text('recipients')->nullable()->default(null);
            $table->unsignedInteger('date_delivered')->nullable()->default(null);
            $table->unsignedInteger('date_dropped')->nullable()->default(null);
            $table->unsignedInteger('date_bounced')->nullable()->default(null);
            $table->unsignedInteger('date_spam')->nullable()->default(null);
            $table->unsignedInteger('date_unsubscribed')->nullable()->default(null);
            $table->unsignedInteger('date_clicked')->nullable()->default(null);
            $table->unsignedInteger('date_opened')->nullable()->default(null);
            $table->unsignedInteger('date_created');
            $table->unsignedInteger('date_updated');

            $table->index(["message_id"], 'idx_tcp_messages_tracker__message_id');
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
