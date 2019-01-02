<?php

use Faker\Generator as Faker;

/**
 * Need overriding:
 * agency_id
 * user_id_staff
 * region_id
 * primary_solicitor_id
 * secondary_solicitor_id
 */
$factory->define(App\AgencyBranch::class, function (Faker $faker) {
    return [
        'address_id' => function() {
            return factory(\App\Address::class)->create(['target_type' => 'Agency Branch'])->id;
        },
        'name' => $faker->city,
        'active' => $faker->boolean(95) ? 1 : 0,
        'email' => $faker->companyEmail,
        'phone' => $faker->phoneNumber,
    ];
});
