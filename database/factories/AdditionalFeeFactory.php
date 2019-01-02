<?php

use Faker\Generator as Faker;

$factory->define(\App\AdditionalFee::class, function (Faker $faker) {
    return [
        'mortgage' => $faker->numberBetween(10,25) * 5,
        'mortgage_redemption' => $faker->numberBetween(10, 24) * 5,
        'leasehold' => $faker->numberBetween(15,20) * 10,
        'archive' => $faker->randomElement([30, 20]),
    ];
});
