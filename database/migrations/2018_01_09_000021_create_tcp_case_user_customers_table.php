<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpCaseUserCustomersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'case_user_customers';
    /**
     * Run the migrations.
     * @table tcp_case_user_customers
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('case_id')->unsigned();
            $table->unsignedInteger('user_id_customer')->unsigned();
            $table->unsignedInteger('customer_number')->comment('This references ths customer on the case eg 1 wold be the lead customer');

            $table->index(["user_id_customer"], 'FK_tcp_case_user_customers_tcp_users');

            $table->index(["case_id"], 'FK_tcp_case_user_customers_tcp_cases');

            $table->foreign('case_id', 'FK_tcp_case_user_customers_tcp_cases')
                ->references('id')->on('cases')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('user_id_customer', 'FK_tcp_case_user_customers_tcp_users')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
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
