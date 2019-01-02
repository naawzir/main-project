<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpDisbursementsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'disbursements';
    /**
     * Run the migrations.
     * @table tcp_disbursements
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 45)->nullable()->default(null);
            $table->decimal('cost', 13, 2)->default('0.00');
            $table->unsignedInteger('both_vendors')->default('0');
            $table->unsignedInteger('active')->default('1');
            $table->string('type', 20)->default('buyer');
            $table->unsignedInteger('standard')->default('0');
            $table->text('subtitle')->nullable()->default(null);
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
