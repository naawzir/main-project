<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpUserCustomersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'user_customers';
    /**
     * Run the migrations.
     * @table tcp_user_customers
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('address_id')->default('0');
            $table->unsignedInteger('user_id');
            $table->string('title', 10)->nullable()->default(null)->comment('should be removed later');
            $table->string('forename', 150)->nullable()->default(null)->comment('should be removed later');
            $table->string('surname', 200)->nullable()->default(null)->comment('should be removed later');
            $table->string('email', 150)->nullable()->default(null)->comment('should be removed later');
            $table->unsignedInteger('international_address')->nullable()->default('0');
            $table->unsignedInteger('aml_search_completed')->nullable()->default('0');

            $table->index(["address_id"], 'idx_tcp_user_customers__address_id');

            $table->index(["user_id"], 'idx_tcp_user_customers__user_id');
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

