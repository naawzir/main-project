<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $solicitorCount = 0;
        factory(\App\Solicitor::class, 10)
            ->create()
            ->each(function(\App\Solicitor $solicitor) use (&$solicitorCount) {
                $solicitorCount++;
                $solicitor
                    ->offices()
                    ->saveMany(
                        factory(\App\SolicitorOffice::class, random_int(1, 10))
                            ->create()
                            ->each(function(\App\SolicitorOffice $solicitorOffice) use ($solicitor) {
                                $solicitorOffice
                                    ->users()
                                    ->saveMany(
                                        factory(\App\SolicitorUser::class, random_int(1, 15))
                                            ->make(['solicitor_id' => $solicitor->id])
                                    );

                                    $solicitorOffice->feeStructures()->saveMany([
                                        factory(\App\LegalFee::class)
                                            ->create(['case_type' => 'Purchase']),
                                        factory(\App\LegalFee::class)
                                            ->create(['case_type' => 'Purchase', 'price_from' => 100001, 'price_to' => 250000]),
                                        factory(\App\LegalFee::class)
                                            ->create(['case_type' => 'Sale']),
                                        factory(\App\LegalFee::class)
                                            ->create(['case_type' => 'Sale', 'price_from' => 100001, 'price_to' => 250000]),
                                    ]);
                            })
                    );
                $solicitor->default_office = $solicitor->offices()->first()->id;
                $solicitor->save();

                $this->command->getOutput()->writeln('<info>Seeded Solicitor:</info> ' . $solicitor->name . ' (' . $solicitorCount . '/10)');
            });
    }
}