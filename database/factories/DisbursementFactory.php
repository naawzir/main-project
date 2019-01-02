<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Disbursement::class, function (Faker $faker) {
    $name = $faker->unique()->disbursement;

    return [
        'name' => $name,
        'slug' => $faker->uuid,
        'transaction' => stripos($name, 'search') === false ? 'sale' : 'purchase',
        'cost' => $faker->randomFloat(2, 1, 4000),
        'type' => 'case',
        'date_created' => $faker->dateTimeThisMonth->format('U'),
        'date_updated' => $faker->dateTimeThisMonth->format('U'),
    ];
});

$factory->state(\App\Disbursement::class, 'fee', function (Faker $faker) {
    return [
        'name' => $faker->fee,
    ];
});

$factory->state(\App\Disbursement::class, 'search', function (Faker $faker) {
    return [
        'name' => $faker->search,
        'transaction' => 'purchase',
    ];
});
