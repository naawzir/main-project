<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpComplianceTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'compliance';
    /**
     * Run the migrations.
     * @table tcp_compliance
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('case_id', 32)->nullable()->default(null);
            $table->char('owner_id', 32)->nullable()->default(null);
            $table->integer('datecreated')->nullable()->default(null);
            $table->integer('dateusermodified')->nullable()->default(null);
            $table->integer('completion_total_pct')->nullable()->default('0');
            $table->text('completion_sections_pct')->nullable()->default(null);
            $table->text('completion_sections')->nullable()->default(null);
            $table->string('status', 50);
            $table->string('slug');
            $table->text('esign')->nullable()->default(null);
            $table->integer('completion_date')->nullable()->default(null);
            $table->char('completion_user_id', 32)->nullable()->default(null);
            $table->char('document_id', 32)->nullable()->default(null);
            $table->char('compiled_document_id', 32)->nullable()->default(null);
            $table->integer('terms_accepted')->nullable()->default('0');
            $table->integer('privacy_policy_accepted')->nullable()->default('0');
            $table->string('conveyancing_email', 50)->nullable()->default(null);

            $table->unique(["slug"], 'slug');
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
