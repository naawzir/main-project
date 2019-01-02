<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpAgenciesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'agencies';
    /**
     * Run the migrations.
     * @table tcp_agencies
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 128)->default('');
            $table->unsignedInteger('active')->default('1');
            $table->string('email', 128)->default('');
            $table->string('url', 100)->nullable()->default(null);
            $table->string('primary_contact', 250)->default('');
            $table->string('primary_contact_number', 50)->nullable()->default('');
            $table->string('primary_contact_email', 128)->nullable()->default('');
            $table->string('telephone', 50)->default('');
            $table->string('conveyancing_telephone', 50)->nullable()->default(null);
            $table->unsignedInteger('address_id')->nullable()->default(null);
            $table->text('style')->nullable()->default(null);
            $table->unsignedInteger('use_custom_points')->nullable()->default('0');
            $table->integer('subfee_transfer')->nullable()->default('0');
            $table->integer('subfee_mortgage_dis')->nullable()->default('0');
            $table->integer('subfee_leasehold_dis')->nullable()->default('0');
            $table->integer('subfee_sdlt_dis')->nullable()->default('0');
            $table->integer('subfee_archive_dis')->nullable()->default('0');
            $table->integer('subfee_transfer_dis')->nullable()->default('0');
            $table->integer('subfee_redemp_mortgage_dis')->nullable()->default('0');
            $table->integer('subfee_redemp_mortgage')->nullable()->default('0');
            $table->unsignedInteger('pa_money_account')->default('0');
            $table->string('compliance_style_slug', 30)->nullable()->default(null);
            $table->unsignedInteger('fee_structure_id')->nullable()->default('0');
            $table->string('user_id_staff', 250)->nullable()->default('');
            $table->string('user_id_bmd', 250)->nullable()->default('');
            $table->integer('allow_comparison')->default('1');
            $table->string('logo_uploaded');
            $table->unsignedInteger('date_created')->default('0');
            $table->unsignedInteger('date_updated')->default('0');

            $table->index(["address_id"], 'idx_tcp_agencies__address_id');
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
