<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\LastCaseReference;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $output = $this->command->getOutput();

        factory(\App\User::class)->states('role:account-manager')->create([
            'active' => 1,
            'title' => 'Mr',
            'forenames' => 'Sean',
            'surname' => 'Nessworthy',
            'email' => 'sean@nessworthy.me',
            'username' => 'sean',
            'password' => bcrypt('rammstein'),
            'hashsalt' => '',
        ]);
        $output->writeln('<info>User created:</info> sean');

        foreach (\App\UserRole::all() as $role) {
            $user = factory(\App\User::class)->create([
                'user_role_id' => $role->id,
                'username' => $role->dashboard_title,
            ]);
            $output->writeln("<info>User created:</info> {$role->dashboard_title}");
            /*if (in_array ($role->id, [8,9])) {
                factory(\App\AgentUser::class)->create([
                    'agency_id' => 1,
                    'agency_branch_id' => 1,
                    'user_id' => $user->id,
                    'points' => 0,
                    'points_wallet' => 0,
                    'valuer' => 0,
                    'registered_for_points' => 0,
                    'show_survey' => 1,
                    'position' => 'neg',
                    'rewards_email' => '',
                ]);
                $output->writeln("<info>AgentUser created:</info> {$role->dashboard_title}");
            }*/

            if (in_array ($role->id, [7])) {

                $user = User::where('user_role_id', 7)->first();
                /*$bOwner = $user->agencyUser;
                $bOwnerAgency = $bOwner->agency_id;
                $bOwnerBranch = $bOwner->agency_branch_id;
                dd($bOwner, $bOwnerAgency, $bOwnerBranch);*/
                factory(\App\AgentUser::class)->create([
                    'agency_id' => 1,
                    'agency_branch_id' => 521,
                    'user_id' => $user->id,
                    'points' => 0,
                    'points_wallet' => 0,
                    'valuer' => 0,
                    'registered_for_points' => 0,
                    'show_survey' => 1,
                    'position' => 'neg',
                    'rewards_email' => '',
                ]);
                $output->writeln("<info>AgentUser created:</info> {$role->dashboard_title}");

                $statuses = [
                    'prospect',
                    'instructed',
                    'instructed_unpanelled',
                    'completed',
                    'aborted'
                ];

                foreach ($statuses as $status) {
                    /*
                    * I'm using this instead of the Reference Generator stuff Sean for now (Riz)
                    */
                   /* $referenceModel = LastCaseReference::find(1);
                    $newReference = $referenceModel->first()->reference + 1;
                    $referenceModel->reference = $newReference;
                    $referenceModel->update();*/

                    $transaction = factory(\App\Transaction::class)->create([
                        'active' => 1,
                        'status' => $status,
                        'type' => 'sale',
                        //'reference' => $newReference,
                        'agency_id' => 1,
                        'agency_branch_id' => 521,
                        'agent_user_id' => $user->id
                    ]);

                   $case = factory(\App\ConveyancingCase::class)
                        ->states([
                            'solicitor:random'
                        ])
                        ->create([
                            'status' => $status
                        ]);

                    $sCollection = factory(\App\ServiceCollection::class)->create([
                        'target_id' => $case->id,
                        'target_type' => 'conveyancing_case'
                    ]);

                    factory(\App\TransactionServiceCollection::class)->create([
                        'transaction_id' => $transaction->id,
                        'service_collection_id' => $sCollection->id
                    ]);
                }

                foreach ($statuses as $status) {
                    /*
                    * I'm using this instead of the Reference Generator stuff Sean for now (Riz)
                    */
                    /*$referenceModel = LastCaseReference::find(1);
                    $newReference = $referenceModel->first()->reference + 1;
                    $referenceModel->reference = $newReference;
                    $referenceModel->update();*/

                    $transaction = factory(\App\Transaction::class)->create([
                        'active' => 1,
                        'status' => $status,
                        'type' => 'purchase',
                        //'reference' => $newReference,
                        'agency_id' => 1,
                        'agency_branch_id' => 521,
                        'agent_user_id' => $user->id
                    ]);

                    factory(\App\ConveyancingCase::class)
                        ->states([
                            'solicitor:random'
                        ])
                        ->create([
                            'status' => $status
                        ]);
                        /*->each(function(\App\ConveyancingCase $case) use (&$caseCount) {
                            $caseCount++;
                            $this->command->getOutput()->writeln('<info>Seeded Case:</info> ' . $case->reference . ' (' . $caseCount . '/100)');
                        });*/

                    $sCollection = factory(\App\ServiceCollection::class)->create([
                        'target_id' => $case->id,
                        'target_type' => 'conveyancing_case'
                    ]);

                    factory(\App\TransactionServiceCollection::class)->create([
                        'transaction_id' => $transaction->id,
                        'service_collection_id' => $sCollection->id
                    ]);
                }
            }
        }
    }
}
