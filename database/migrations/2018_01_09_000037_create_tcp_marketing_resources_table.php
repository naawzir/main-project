<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpMarketingResourcesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'marketing_resources';
    /**
     * Run the migrations.
     * @table tcp_marketing_resources
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('src');
            $table->unsignedInteger('allow_download')->default('0');
            $table->integer('position');
            $table->string('name');
            $table->unsignedInteger('available')->default('1');
            $table->string('category', 100);
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');
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

