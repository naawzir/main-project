<?php

use Illuminate\Database\Seeder;

class PostcodeCacheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Cache::class, 100)->create();
    }
}
