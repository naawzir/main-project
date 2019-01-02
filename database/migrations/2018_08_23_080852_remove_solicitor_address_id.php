<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSolicitorAddressId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('solicitors', 'address_id')) return;
        Schema::table('solicitors', function (Blueprint $table) {
            $table->dropColumn('address_id');
        });
    }
}
