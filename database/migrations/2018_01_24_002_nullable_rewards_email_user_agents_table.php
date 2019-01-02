<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class NullableRewardsEmailUserAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up()
    {
        Schema::table('user_agents', function ($table) {
            $table->string('rewards_email', 255)->nullable()->change();
        });
    }
}
