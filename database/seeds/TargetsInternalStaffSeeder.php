<?php

use Illuminate\Database\Seeder;
use App\User;
use App\TargetsInternalStaff;

class TargetsInternalStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $output = $this->command->getOutput();

        // get each Account Manager and the Account Manager Lead and populate the table
        $accountManagers = User::where([
            'user_role_id', '=', [5, 6]
        ])->get();

        foreach ($accountManagers as $accountManager) {
            factory(\App\TargetsInternalStaff::class)->create([
                'user_id' => $accountManager->id,
                //'date_created' => time(),
                //'date_updated' => time(),
                'date_from' => time(),
                'target' => 22,
            ]);
            $output->writeln("<info>Target created:</info>");
        }
    }
}
