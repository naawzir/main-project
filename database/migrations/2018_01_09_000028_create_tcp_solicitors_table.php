<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpSolicitorsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'solicitors';
    /**
     * Run the migrations.
     * @table tcp_solicitors
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('address_id')->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->string('email', 250)->nullable()->default(null);
            $table->unsignedInteger('default_office')->nullable()->default(null);
            $table->integer('active')->nullable()->default('1');
            $table->unsignedInteger('date_created')->nullable()->default(null);
            $table->unsignedInteger('date_updated')->nullable()->default(null);
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

