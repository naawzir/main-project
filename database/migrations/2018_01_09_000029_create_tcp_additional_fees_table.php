<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTcpAdditionalFeesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'additional_fees';
    /**
     * Run the migrations.
     * @table tcp_fee_structures
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('agency_id')->nullable()->default(null)->unsigned();
            $table->unsignedInteger('solicitor_id')->nullable()->default(null)->unsigned();
            $table->unsignedInteger('active')->default('1');
            $table->string('structure_name', 250);
            $table->decimal('subfee_mortgage', 13, 2)->default('0.00');
            $table->decimal('subfee_redemp_mortgage', 13, 2)->default('0.00');
            $table->decimal('subfee_leasehold', 13, 2)->default('0.00');
            $table->decimal('subfee_sdlt', 13, 2)->default('0.00');
            $table->decimal('subfee_archive', 13, 2)->default('0.00');
            $table->unsignedInteger('date_created');
            $table->unsignedInteger('date_updated');

            $table->index(["solicitor_id"], 'FK_tcp_additional_fees_tcp_solicitors');

            $table->index(["agency_id"], 'FK_tcp_additional_fees_tcp_agencies');


            $table->foreign('agency_id', 'FK_tcp_additional_fees_tcp_agencies')
                ->references('id')->on('agencies')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('solicitor_id', 'FK_tcp_additional_fees_tcp_solicitors')
                ->references('id')->on('solicitors')
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
