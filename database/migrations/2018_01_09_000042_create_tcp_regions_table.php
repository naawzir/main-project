<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpRegionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'regions';
    /**
     * Run the migrations.
     * @table tcp_regions
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 200)->default('');
            $table->text('postcodes')->nullable()->default(null);
            $table->string('email', 200)->default('');
            $table->unsignedSmallInteger('code')->nullable()->default(null);
            $table->string('public_title', 150)->nullable()->default(null);
            $table->string('investor_title', 150)->nullable()->default(null);
            $table->string('short_title', 50)->nullable()->default(null);
            $table->integer('active')->nullable()->default('1');
            $table->string('logo', 200)->nullable()->default(null);

            $table->index(["code"], 'idx_code');
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

