<?php

use Faker\Generator as Faker;

$factory->define(App\SolicitorOffice::class, function (Faker $faker) {
    return [
        'address_id' => function() {
            return factory(\App\Address::class)->create(['target_type' => 'Solicitor Office'])->id;
        },
        'office_name' => $faker->company,
        'slug' => $faker->uuid,
        'phone' => $faker->phoneNumber,
        'email' => $faker->optional()->companyEmail,
        'tm_ref' => $faker->optional(0.25)->numberBetween(20000,50000),
        'capacity' => $faker->optional(0.05)->numberBetween(1,50),
        'status' => 'Active',
    ];
});

$factory->state(App\SolicitorOffice::class, 'onboarding:bdm', ['status' => 'Pending']);
$factory->state(App\SolicitorOffice::class, 'onboarding:panel-manager', function (Faker $faker) {
    return [
        'status' => $faker->randomElement(['Onboarding', 'SentToTM'])
    ];
});
