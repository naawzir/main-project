<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpAgencyBranchesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'agency_branches';
    /**
     * Run the migrations.
     * @table tcp_agency_branches
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('agency_id')->nullable()->default(null);
            $table->unsignedInteger('address_id')->nullable()->default(null);
            $table->string('name')->default('');
            $table->unsignedInteger('active')->default('1');
            $table->unsignedInteger('user_id_staff');
            $table->string('email', 250)->default('');
            $table->unsignedInteger('region_id')->nullable()->default(null);
            $table->string('phone', 50)->default('');
            $table->unsignedInteger('primary_solicitor_id')->nullable()->default(null);
            $table->unsignedInteger('secondary_solicitor_id')->nullable()->default(null);
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');

            $table->index(["agency_id"], 'idx_tcp_agency_branches__agency_id');

            $table->index(["address_id"], 'idx_tcp_agency_branches__address_id');

            $table->index(["region_id"], 'idx_tcp_agency_branches__region_id');

            $table->index(["secondary_solicitor_id"], 'idx_tcp_agency_branches__secondary_solicitor_id');

            $table->index(["user_id_staff"], 'idx_tcp_agency_branches__user_id_staff');

            $table->index(["primary_solicitor_id"], 'idx_tcp_agency_branches__primary_solicitor_id');
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
