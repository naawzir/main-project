<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpFeatureUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'feature_users';
    /**
     * Run the migrations.
     * @table tcp_feature_users
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
            $table->string('feature_name', 30);
            $table->unsignedInteger('date_seen');
            $table->unsignedTinyInteger('skipped')->nullable()->default(null)->comment('0 - No skip, 1 - Skipped, NULL - not possible to skip.');

            $table->index(["user_id"], 'idx_tcp_feature_users__user_id');
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
