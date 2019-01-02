<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpCaseFeesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'case_fees';
    /**
     * Run the migrations.
     * @table tcp_case_fees
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('case_id');
            $table->decimal('legal_fee', 13, 2)->nullable()->default('0.00');
            $table->decimal('referral_fee', 13, 2)->nullable()->default('0.00');
            $table->decimal('agent_referral_fee', 13, 2)->nullable()->default('0.00');
            $table->decimal('mortgage_fee', 13, 2)->nullable()->default('0.00');
            $table->decimal('redemption_mortgage_fee', 13, 2)->nullable()->default('0.00');
            $table->decimal('leasehold_fee', 13, 2)->nullable()->default('0.00');
            $table->decimal('stamp_duty_land_tax', 13, 2)->nullable()->default('0.00');
            $table->decimal('archive_fee', 13, 2)->nullable()->default('0.00');
            $table->integer('date_created')->nullable()->default('0');
            $table->integer('date_updated')->nullable()->default('0');

            $table->index(["case_id"], 'idx_tcp_case_fees__case_id');

            $table->unique(["case_id"], 'case_id');
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
