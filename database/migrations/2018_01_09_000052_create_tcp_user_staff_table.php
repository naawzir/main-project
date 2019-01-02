<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpUserStaffTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'user_staff';
    /**
     * Run the migrations.
     * @table tcp_user_staff
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->text('bio')->nullable()->default(null);
            $table->string('image', 65)->nullable()->default(null);
            $table->integer('bio_shown')->nullable()->default('0');
            $table->string('position', 50)->nullable()->default(null);
            $table->text('job_title')->nullable()->default(null);

            $table->index(["user_id"], 'idx_tcp_user_staff__user_id');
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

