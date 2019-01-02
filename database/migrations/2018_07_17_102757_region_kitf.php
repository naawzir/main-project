<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RegionKitf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('regions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('regions', function(Blueprint $table) {
            $table->unsignedInteger('id', true)->length(10);
            $table->string('title', 200)->default('');
            $table->text('postcodes');
            $table->string('email', 200)->default('');
            $table->unsignedSmallInteger('code')->length(5)->default(null);
            $table->string('public_title', 150)->default(null);
            $table->string('investor_title', 150)->default(null);
            $table->string('short_title', 50)->default(null);
            $table->integer('active')->default(1);
            $table->string('logo', 200)->default(null);
            $table->index('code', 'idx_code');
        });
    }
}
