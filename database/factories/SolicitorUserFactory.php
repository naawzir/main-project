<?php

use Faker\Generator as Faker;

/**
 * Solicitor and office IDs should be injected in, not defined here.
 */
$factory->define(App\SolicitorUser::class, function (Faker $faker) {
    $title = str_replace('.', '', $faker->title);
    $forename = $faker->firstName;
    $surname = $faker->lastName;
    $phone = $faker->optional(0.8)->phoneNumber;
    $phoneOther = $faker->optional(0.08)->phoneNumber;
    $email = $faker->optional(0.95)->companyEmail;
    $active = $faker->boolean(95) ? 1 : 0;

    return [
        'user_id' => function() use ($title, $forename, $surname, $phone, $phoneOther, $email, $active) {
            return factory(\App\User::class)->states('role:solicitor')->create([
                'title' => $title,
                'forenames' => $forename,
                'surname' => $surname,
                'phone' => $phone,
                'phone_other' => $phoneOther,
                'email' => $email,
                'active' => $active,
            ])->id;
        },
        'slug' => $faker->uuid,
        'title' => $title,
        'forenames' => $forename,
        'surname' => $surname,
        'phone' => $phone,
        'phone_other' => $phoneOther,
        'email' => $email,
        'active' => $active,
    ];
});
