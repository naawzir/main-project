<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(StaffSeeder::class);
        // $this->call(PostcodeCacheSeeder::class);
        $this->call(SolicitorSeeder::class);
        $this->call(AgencySeeder::class);
        $this->call(CaseSeeder::class);
        $this->call(TargetsInternalStaffSeeder::class);
    }
}
