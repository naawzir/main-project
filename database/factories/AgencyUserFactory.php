<?php

use Faker\Generator as Faker;

$factory->define(App\AgentUser::class, function (Faker $faker) {
    return [
        'registered_for_points' => $faker->boolean(30) ? 1 : 0,
        'show_survey' => $faker->boolean ? 1 : 0,
        'points' => $faker->randomNumber,
        'points_wallet' => $faker->randomNumber,
        'rewards_email' => $faker->unique()->safeEmail,
    ];
});
