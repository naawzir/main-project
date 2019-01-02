<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Upgrades the audits plugin from 5.X to 7.X
 */
class AuditsUpgrade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('audits', 'tags')) {
            Schema::table('audits', function (Blueprint $table) {
                $table->string('tags')->nullable();
            });
        }
        if (!Schema::hasColumn('audits', 'user_type')) {
            Schema::table('audits', function (Blueprint $table) {
                $table->string('user_type')->nullable();
            });

            // Set the user_type value and keep the timestamp values.
            DB::table('audits')->update([
                'user_type'  => \App\User::class,
                'created_at' => DB::raw('created_at'),
                'updated_at' => DB::raw('updated_at'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
