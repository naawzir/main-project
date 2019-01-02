<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class UserRoleData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('user_roles')->truncate();

        DB::table('user_roles')->insert([
            'id' => 1,
            'title' => 'Superusers',
            'dashboard_title' => 'superuser',
            'description' => 'Software Development',
            'super_user' => 1,
            'active' => 0,
            'base_permissions' => '1,2,3,4',
            'date_created' => 1517568285,
            'date_updated' => 1528811712,
        ]);

        DB::table('user_roles')->insert([
            'id' => 2,
            'title' => 'Director',
            'dashboard_title' => 'director',
            'description' => 'Ben Ridgway',
            'super_user' => 0,
            'active' => 1,
            'base_permissions' => '2,4,5,22,23,24,25,26,27,28,29,30,31,32,33',
            'date_created' => 1517568285,
            'date_updated' => 1521458101,
        ]);

        DB::table('user_roles')->insert([
            'id' => 3,
            'title' => 'BDM',
            'dashboard_title' => 'bdm',
            'description' => 'Business Development Managers',
            'super_user' => 0,
            'active' => 1,
            'base_permissions' => '1,4,5,22,23,24,25,26,27,28,29,30,31,32,33',
            'date_created' => 1517568285,
            'date_updated' => 1517568285
        ]);

        DB::table('user_roles')->insert([
            'id' => 4,
            'title' => 'Panel Management',
            'dashboard_title' => 'panel-manager',
            'description' => 'Alison Lamb',
            'super_user' => 0,
            'active' => 1,
            'base_permissions' => '1,4,5,22,23,24,25,26,27,28,29,30,31,32,33',
            'date_created' => 1517568285,
            'date_updated' => 1517568285,
        ]);

        DB::table('user_roles')->insert([
            'id' => 5,
            'title' => 'Account Management Lead',
            'dashboard_title' => 'account-manager-lead',
            'description' => 'Amanda Whitesmith',
            'super_user' => 0,
            'active' => 1,
            'base_permissions' => '1,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33',
            'date_created' => 1517568285,
            'date_updated' => 1517568285,
        ]);

        DB::table('user_roles')->insert([
            'id' => 6,
            'title' => 'Account Management',
            'dashboard_title' => 'account-manager',
            'description' => 'Account Management',
            'super_user' => 0,
            'active' => 1,
            'base_permissions' => '1,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33',
            'date_created' => 1517568285,
            'date_updated' => 1517568285,
        ]);

        DB::table('user_roles')->insert([
            'id' => 7,
            'title' => 'Business Owner',
            'dashboard_title' => 'business-owner',
            'description' => 'Business Owner (Agency)',
            'super_user' => 0,
            'active' => 1,
            'base_permissions' => '2,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,30,31,32,33',
            'date_created' => 1517568285,
            'date_updated' => 1517568285,
        ]);

        DB::table('user_roles')->insert([
            'id' => 8,
            'title' => 'Branch Manager',
            'dashboard_title' => 'branch-manager',
            'description' => 'Branch Manager (Agency)',
            'super_user' => 0,
            'active' => 1,
            'base_permissions' => '2,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,30,31,32,33',
            'date_created' => 1517568285,
            'date_updated' => 1517568285,
        ]);

        DB::table('user_roles')->insert([
            'id' => 9,
            'title' => 'Agent',
            'dashboard_title' => 'agent',
            'description' => 'Previously Level 4 Agent Staff (Agency users)',
            'super_user' => 0,
            'active' =>  1,
            'base_permissions' => '2,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,30,31,32,33',
            'date_created' => 1517568285,
            'date_updated' => 1517568285,
        ]);

        DB::table('user_roles')->insert([
            'id' => 10,
            'title' => 'Customer',
            'dashboard_title' => 'customer',
            'description' => 'Customers',
            'super_user' => 0,
            'active' => 1,
            'base_permissions' => '3',
            'date_created' => 1517568285,
            'date_updated' => 1521213319,
        ]);

        DB::table('user_roles')->insert([
            'id' => 11,
            'title' => 'Solicitor',
            'dashboard_title' => 'solicitor',
            'description' => 'Solicitors',
            'super_user' => 0,
            'active' => 1,
            'base_permissions' => '3',
            'date_created' => 1531129500,
            'date_updated' => 1531129500,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No turning back!
    }
}
