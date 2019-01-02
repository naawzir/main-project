<?php

use Faker\Generator as Faker;

$factory->define(App\ConveyancingCase::class, function (Faker $faker) {
    return [
        // related_case_id
        //'address_id' => function() { return factory(\App\Address::class)->create(['target_type' => 'Case'])->id; },
        'slug' => $faker->uuid,
        // agency_id
        // agency_branch_id
        // user_id_agent
        // fee_structure_id
        // user_id_staff
        // solicitor_id
        // solicitor_office_id
        // user_id_created
        'reference' => $faker->unique()->numberBetween(1000, 99999),
        'status' => 'pending', // STATE
        // date_completed
        // date_archived
        // date_prospect
        // notes
        // notes_tcp
        'active' => 1,
        // milestone
        // milestone_updated_date
        'src_required' => $faker->randomElement([0, 1]),
        'type' => $faker->randomElement(['buying', 'selling', 'mortgage']), // STATE
        // date_instructed
        // date_declined
        // panelled
        'source' => $faker->randomElement(['TCP', 'iam-sold']),
        // referrer_name
        // date_abeyance
        // date_on_hold
        // date_aborted
        // archive_reason
        'lead_source' => $faker->randomElement(['seller_sstc', 'seller_new_take_on','buyer_sstc', 'none', 'seller_welcome_call', 'seller_on_market_already']),
        //'duplicate_of_id' => null,
        'mortgage_new' => $faker->randomElement([0,1]),
        'mortgage_redeem' => $faker->randomElement([0,1]),
        'alerts' => $faker->randomElement([0,1]),
        // date_exchanged
        'price' => $faker->numberBetween(5000,10000000),
        'tenure' => $faker->randomElement(['freehold', 'leasehold']),
        'mortgage' => $faker->randomElement([0,1]),
        'offer' => $faker->randomElement([0,1]),
        'new' => $faker->randomElement([0,1]),
        // date_active
        'second_home' => $faker->randomElement([0,1]),
        'discount' => null,
        // contact_date
        // contact_time
        // aml_fees_paid
        // all_aml_searches_complete
        // date_last_contacted
        // abort_code
        // date_created
        // date_updated
        // date_aml_fees_paid
    ];
});

/*$factory->state(\App\ConveyancingCase::class, 'staff:random', function() {
    $user = \App\User::where('user_role_id', 6)->inRandomOrder()->take(1)->first();

    return [
        'user_id_staff' => $user->id,
        'user_id_created' => $user->id,
    ];
});*/

$factory->state(\App\ConveyancingCase::class, 'solicitor:random', function() {
    /** @var \App\SolicitorOffice $solicitorOffice */
    $solicitorOffice = \App\SolicitorOffice::where('status','Active')->inRandomOrder()->take(1)->first();

    return [
        //'solicitor_id' => $solicitorOffice->solicitor->id,
        //'solicitor_office_id' => $solicitorOffice->id
    ];
});
