<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpLoginsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'logins';
    /**
     * Run the migrations.
     * @table tcp_logins
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->default(null);
            $table->unsignedInteger('session_id')->nullable()->default(null);
            $table->unsignedInteger('date_created')->nullable()->default(null);
            $table->unsignedInteger('date_updated')->nullable()->default(null);
            $table->unsignedInteger('datetime_logged_out')->nullable()->default(null);

            $table->index(["user_id"], 'idx_tcp_logins__user_id');

            $table->index(["session_id"], 'idx_tcp_logins__session_id');
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
