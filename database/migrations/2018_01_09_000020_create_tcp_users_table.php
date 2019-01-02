<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'users';
    /**
     * Run the migrations.
     * @table tcp_users
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('active')->default('1');
            $table->unsignedInteger('date_created');
            $table->unsignedInteger('date_updated');
            $table->string('title', 20)->default('');
            $table->string('forenames', 128)->default('');
            $table->string('surname', 128)->default('');
            $table->string('email', 64)->default('');
            $table->string('username', 128)->default('');
            $table->string('password')->default('');
            $table->string('hashsalt', 32)->default('');
            $table->string('phone', 64)->default('');
            $table->string('phone_other', 64)->default('');
            $table->char('activation_code', 32)->default('');
            $table->string('notes')->nullable()->default(null);
            $table->unsignedInteger('user_role_id');
            $table->rememberToken();

            $table->index(["user_role_id"], 'idx_tcp_users__user_role_id');
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
