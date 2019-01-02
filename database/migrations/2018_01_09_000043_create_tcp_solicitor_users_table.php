<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpSolicitorUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'solicitor_users';
    /**
     * Run the migrations.
     * @table tcp_solicitor_users
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('solicitor_id');
            $table->unsignedInteger('solicitor_office_id')->nullable()->default(null);
            $table->string('title', 50)->nullable()->default(null);
            $table->string('forenames', 250)->nullable()->default(null);
            $table->string('surname', 250)->nullable()->default(null);
            $table->string('phone', 250)->nullable()->default(null);
            $table->string('phone_other', 250)->nullable()->default(null);
            $table->string('email', 250)->nullable()->default(null);
            $table->unsignedInteger('active')->default('1');
            $table->unsignedInteger('date_created')->nullable()->default(null);
            $table->unsignedInteger('date_updated')->nullable()->default(null);

            $table->index(["solicitor_id"], 'idx_tcp_solicitor_users__solicitor_id');

            $table->index(["solicitor_office_id"], 'idx_tcp_solicitor_users__solicitor_office_id');
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

