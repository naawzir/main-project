<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class ActiveTcpPermissions extends Migration
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
        if (Schema::hasColumn('permissions', 'active')) return;
        Schema::table('permissions', function ($table) {
            $table->boolean('active')->unsigned()->default(1);
        });
    }
}
