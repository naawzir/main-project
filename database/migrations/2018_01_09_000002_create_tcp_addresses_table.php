<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpAddressesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'addresses';
    /**
     * Run the migrations.
     * @table tcp_addresses
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('target_type')->nullable()->default(null);
            $table->string('building_name', 250)->nullable()->default(null);
            $table->string('building_number', 250)->nullable()->default(null);
            $table->string('address_line_1')->nullable()->default(null);
            $table->string('address_line_2')->nullable()->default(null);
            $table->string('address_line_3')->nullable()->default(null);
            $table->string('town')->nullable()->default(null);
            $table->string('county')->nullable()->default(null);
            $table->string('postcode')->nullable()->default(null);
            $table->unsignedInteger('date_created');
            $table->unsignedInteger('date_updated');
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
