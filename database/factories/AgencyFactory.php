<?php

use Faker\Generator as Faker;

$factory->define(\App\Agency::class, function (Faker $faker) {
    $hasFees = $faker->boolean(50);

    return [
        'name' => $faker->company,
        'active' => $faker->boolean(95) ? 1 : 0,
        'email' => $faker->companyEmail,
        'url' => $faker->url,
        'primary_contact' => $faker->name,
        'primary_contact_number' => $faker->phoneNumber,
        'primary_contact_email' => $faker->companyEmail,
        'telephone' => $faker->phoneNumber,
        'conveyancing_telephone' => $faker->optional(0.1)->phoneNumber,
        'address_id' => function() {
            return factory(\App\Address::class)->create(['target_type' => 'Agency'])->id;
        },
        //'fee_structure_id' => null,
        'use_custom_points' => 0,
        'subfee_transfer' => $hasFees ? ($faker->numberBetween(6,10) * 5) : 0,
        'subfee_mortgage_dis' => $hasFees ? ($faker->numberBetween(10,25) * 5) : 0,
        'subfee_leasehold_dis' => $hasFees ? ($faker->numberBetween(15,20) * 10) : 0,
        'subfee_sdlt_dis' => $hasFees ? ($faker->numberBetween(10, 20) * 5) : 0,
        'subfee_archive_dis' => $hasFees ? $faker->randomElement([30, 20]) : 0,
        'subfee_transfer_dis' => $hasFees ? $faker->numberBetween(40, 50) : 0,
        'subfee_redemp_mortgage_dis' => $hasFees ? ($faker->numberBetween(10, 24) * 5) : 0,
        'subfee_redemp_mortgage' => $hasFees ? ($faker->numberBetween(10, 25) * 5) : 0,
        'pa_money_account' => 0,
        'compliance_style_slug' => null,
        'user_id_staff' => '',
        //'user_id_bmd' => '',
        'allow_comparison' => $faker->boolean(80) ? 0 : 1,
    ];
});
