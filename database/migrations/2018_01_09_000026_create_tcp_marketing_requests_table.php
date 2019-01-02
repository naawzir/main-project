<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTcpMarketingRequestsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'marketing_requests';
    /**
     * Run the migrations.
     * @table tcp_marketing_requests
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('marketing_resource_id');
            $table->unsignedInteger('user_id_agent');
            $table->char('assigned_to', 32);
            $table->string('artwork_use', 20)->default('');
            $table->string('dimensions', 100);
            $table->string('orientation', 10);
            $table->string('use_type', 5);
            $table->integer('quote')->default('0');
            $table->unsignedInteger('quantity')->default('0');
            $table->string('other_information')->nullable()->default(null);
            $table->string('telephone', 25);
            $table->integer('use_ad')->default('0');
            $table->integer('date_required');
            $table->integer('date_created');
            $table->integer('date_updated');
            $table->integer('date_completed');
            $table->string('number_of_case_studies', 10);

            $table->index(["marketing_resource_id"], 'idx_tcp_marketing_requests__marketing_resource_id');

            $table->index(["user_id_agent"], 'idx_tcp_marketing_requests__user_id_agent');
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
