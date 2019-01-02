<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpPointAwardsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'point_awards';
    /**
     * Run the migrations.
     * @table tcp_point_awards
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('case_id')->comment('Previously pkey which I\'m quite certain is always the case ID');
            $table->unsignedInteger('user_id_agent');
            $table->integer('points')->default('0');
            $table->integer('claim')->nullable()->default('0');
            $table->string('claim_status', 50)->nullable()->default('');
            $table->unsignedInteger('date_claimed')->nullable()->default('0');
            $table->unsignedInteger('date_claim_expected_pay')->nullable()->default(null);
            $table->unsignedInteger('date_claim_actual_pay')->nullable()->default(null);
            $table->unsignedInteger('date_expiry')->nullable()->default(null);
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');

            $table->index(["user_id_agent"], 'idx_tcp_point_awards__user_id_agent');

            $table->index(["case_id"], 'idx_tcp_point_awards__case_id');
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

