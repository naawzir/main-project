<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpAuditsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'audits';
    /**
     * Run the migrations.
     * @table tcp_audits
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
            $table->string('event')->nullable()->default(null);
            $table->unsignedInteger('auditable_id')->nullable()->default(null);
            $table->string('auditable_type')->nullable()->default(null);
            $table->text('old_values')->nullable()->default(null);
            $table->text('new_values')->nullable()->default(null);
            $table->text('url')->nullable()->default(null);
            $table->string('ip_address', 45)->nullable()->default(null);
            $table->string('user_agent')->nullable()->default(null);
            $table->string('tags')->nullable();
            $table->timestamps();
            $table->string('user_type')->nullable();

            $table->index(["auditable_id"], 'idx_tcp_audits__auditable_id');

            $table->index(["user_id"], 'idx_tcp_audits__user_id');
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
