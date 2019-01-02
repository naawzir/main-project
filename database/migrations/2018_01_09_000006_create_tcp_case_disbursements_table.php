<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpCaseDisbursementsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'case_disbursements';
    /**
     * Run the migrations.
     * @table tcp_case_disbursements
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
            $table->unsignedInteger('disbursement_id')->unsigned();
            $table->unsignedInteger('user_id_customer');
            $table->float('cost')->default('0');
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');

            $table->index(["case_id"], 'idx_tcp_case_disbursements_tcp_cases');

            $table->index(["user_id_customer"], 'idx_user_id_customer');

            $table->index(["disbursement_id"], 'idx_tcp_case_disbursements_tcp_disbursements');


            $table->foreign('case_id', 'idx_tcp_case_disbursements_tcp_cases')
                ->references('id')->on('cases')
                ->onDelete('no action')
                ->onUpdate('cascade');

            $table->foreign('disbursement_id', 'idx_tcp_case_disbursements_tcp_disbursements')
                ->references('id')->on('disbursements')
                ->onDelete('no action')
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
