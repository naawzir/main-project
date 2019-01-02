<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'active' => $faker->boolean(95) ? 1 : 0,
        'title' => $faker->title,
        'forenames' => $faker->firstName,
        'surname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'username' => $faker->userName,
        'password' => $password ?: $password = bcrypt('rammstein'),
        'mobile' => $faker->optional(0.5)->phoneNumber,
        'phone' => $faker->optional(0.5)->phoneNumber,
        'phone_other' => $faker->optional(0.05)->phoneNumber,
        'sms_marketing_opt_in' => $faker->numberBetween(0, 1),
        'email_marketing_opt_in' => $faker->numberBetween(0, 1)
    ];
});

$factory->state(\App\User::class, 'role:superuser', ['user_role_id' => 1]);
$factory->state(\App\User::class, 'role:director', ['user_role_id' => 2]);
$factory->state(\App\User::class, 'role:bdm', ['user_role_id' => 3]);
$factory->state(\App\User::class, 'role:panel-manager', ['user_role_id' => 4]);
$factory->state(\App\User::class, 'role:account-manager-lead', ['user_role_id' => 5]);
$factory->state(\App\User::class, 'role:account-manager', ['user_role_id' => 6]);
$factory->state(\App\User::class, 'role:business-owner', ['user_role_id' => 7]);
$factory->state(\App\User::class, 'role:branch-manager', ['user_role_id' => 8]);
$factory->state(\App\User::class, 'role:agent', ['user_role_id' => 9]);
$factory->state(\App\User::class, 'role:solicitor', ['user_role_id' => 11]);
