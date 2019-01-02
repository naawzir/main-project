<?php

use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agent = 0;
        factory(\App\Agency::class, 10)
            ->create()
            ->each(function(\App\Agency $agency) use (&$agent) {
                $agent += 1;

                $agency
                    ->branches()
                    ->saveMany(
                        factory(\App\AgencyBranch::class, random_int(1, 10))
                            ->create()
                    );
                $agency->save();
                $this->command->getOutput()->writeln('<info>Seeded Agency:</info> ' . $agency->name . ' (' . $agent . '/10)');
            });
    }
}

