<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DropNavigationSuburlFieldsTcpPermissions extends Migration
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
        if (!Schema::hasColumn('permissions', 'navigation')) return;
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('navigation');
        });

        if (!Schema::hasColumn('permissions', 'sub_url')) return;
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('sub_url');
        });
    }
}
