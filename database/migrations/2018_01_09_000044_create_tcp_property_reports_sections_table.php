<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpPropertyReportsSectionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'property_reports_sections';
    /**
     * Run the migrations.
     * @table tcp_property_reports_sections
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 50)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->string('slug', 50)->nullable()->default(null);
            $table->string('icon', 50)->nullable()->default(null);
            $table->tinyInteger('order')->nullable()->default(null);
            $table->unsignedTinyInteger('public')->default('1');
            $table->tinyInteger('vendor_visibility')->default('1');

            $table->unique(["slug"], 'slug');

            $table->unique(["order"], 'order');
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

