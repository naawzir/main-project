<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisbursementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            while (true) {
                factory(\App\Disbursement::class)->create([
                    'date_created' => time(),
                    'date_updated' => time(),
                ]);
            }
        } catch (\OverflowException $e) {
            // All disbursement names have been used, seeding finished.
        }
    }
}
