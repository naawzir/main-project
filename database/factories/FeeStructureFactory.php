<?php

use Faker\Generator as Faker;

/**
 * Override case_type and fee_structure_id
 */
$factory->define(App\LegalFee::class, function (Faker $faker) {
    return [
        'slug' => $faker->uuid,
        'price_from' => 0,
        'price_to' => 100000,
        'legal_fee' => $faker->numberBetween(20, 100) * 5 - ($faker->boolean(50) ? 0.01 : 0),
        'referral_fee' => $faker->numberBetween(20, 100) * 5 - ($faker->boolean(50) ? 0.01 : 0),
        'active' => 1,
        'case_type' => $faker->boolean() ? 'Purchase' : 'Sale',
    ];
});
