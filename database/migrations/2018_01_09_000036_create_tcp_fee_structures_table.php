<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpFeeStructuresTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'fee_structures';
    /**
     * Run the migrations.
     * @table fee_structures
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('fee_structure_id')->nullable()->default(null);
            $table->double('start')->nullable()->default('0');
            $table->double('end')->nullable()->default('0');
            $table->decimal('legal_fee', 13, 2)->default('0.00');
            $table->decimal('referral_fee', 13, 2)->nullable()->default('0.00');
            $table->decimal('agent_referral_fee', 13, 2)->default('0.00');
            $table->unsignedInteger('active')->default('1');
            $table->string('case_type', 45)->nullable()->default('both');

            $table->index(["fee_structure_id"], 'idx_tcp_fee_structures__additional_fee_id');
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

