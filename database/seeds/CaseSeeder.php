<?php

use Illuminate\Database\Seeder;

class CaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $caseCount = 0;
        factory(\App\ConveyancingCase::class, 100)
            ->states([
                'solicitor:random'
            ])
            ->create()
            ->each(function(\App\ConveyancingCase $case) use (&$caseCount) {
                $caseCount++;
                $this->command->getOutput()->writeln('<info>Seeded Case:</info> ' . $case->reference . ' (' . $caseCount . '/100)');
            });
    }
}
