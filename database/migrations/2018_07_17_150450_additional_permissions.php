<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AdditionalPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->updateOrInsert([
            'id' => 36,
            'name' => 'View Marketing Options',
            'function' => 'View Marketing Options',
            'date_created' => time(),
            'date_updated' => time(),
            'active' => 1,
        ]);

        DB::table('permissions')->updateOrInsert([
            'id' => 37,
            'name' => 'View Points Rewards Options',
            'function' => 'View Points Rewards Options',
            'date_created' => time(),
            'date_updated' => time(),
            'active' => 1,
        ]);

        DB::table('permissions')->updateOrInsert([
            'id' => 38,
            'name' => 'Create User Role',
            'function' => '/userroles/create',
            'date_created' => time(),
            'date_updated' => time(),
            'active' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        array_map([DB::table('permissions'), 'delete'], [36, 37, 38]);
    }
}
