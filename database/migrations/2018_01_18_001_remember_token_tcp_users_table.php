<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class RememberTokenTcpUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'users';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn($this->set_schema_table, 'remember_token')) return;
        Schema::table('users', function (Blueprint $table) {
            $table->rememberToken();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropRememberToken();
        });
    }

}

