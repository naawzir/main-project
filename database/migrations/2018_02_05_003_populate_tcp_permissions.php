<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\UserRole;
use App\Permission;

class PopulateTcpPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up() // add records to permissions
    {
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (1, 'View Admin Dashboard', '/admin/dashboard', 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (2, 'View Agency Dashboard', 'home', 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (3, 'View Customer Dashboard', '/home', 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (4, 'Create Business Owner', 'Create Business Owner', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (5, 'Create Agency User', '/agency/createuser', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (6, 'View Cases', '/cases', 1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (7, 'Create Cases', '/cases/create', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (8, 'Update Cases', '/cases/{id}', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (9, 'Delete Cases', '/cases/{id}/destroy', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (10, 'View Agencies', '/agencies', 0, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (11, 'Create Agencies', '/agencies/create', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (12, 'Update Agencies', '/agencies/{id}', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (13, 'Delete Agencies', '/agencies/{id}/destroy', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (14, 'View Agency Branches', '/branches', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (15, 'Create Agency Branches', '/branches/create', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (16, 'Update Agency Branches', '/branches/{id}', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (17, 'Delete Agency Branches', '/branches/{id}/destroy', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (18, 'View Users', '/users', 1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (19, 'Create Users', '/branches/create', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (20, 'Update Users', '/branches/{id}', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (21, 'Delete Users', '/branches/{id}/destroy', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (22, 'View Solicitors', '/solicitors', 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (23, 'Create Solicitors', '/solicitors/create', 1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (24, 'Update Solicitors', '/solicitors/{id}', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (25, 'Delete Solicitors', '/solicitors/{id}/destroy', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (26, 'View Solicitor Offices', '/offices', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (27, 'Create Solicitor Offices', '/offices/create', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (28, 'Update Solicitor Offices', '/offices/{id}', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (29, 'Delete Solicitor Offices', '/offices/{id}/destroy', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (30, 'View Property Reports', '/propertyreports', 1, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (31, 'Create Property Reports', '', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (32, 'Update Property Reports', '', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (33, 'Delete Property Reports', '', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (34, 'Service Feedback', '/services', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);
        $query = "INSERT INTO `permissions` (`id`, `name`, `function`, `navigation`, `sub_url`, `date_created`, `date_updated`) VALUES (35, 'Solicitor Performance', '/solicitors/performance', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        DB::statement($query);

    }
}
