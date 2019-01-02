<?php

use Faker\Generator as Faker;

$factory->define(App\Address::class, function (Faker $faker) {
    $now = new DateTimeImmutable();
    $created = $faker->dateTimeThisYear->getTimestamp();

    return [
        'building_name' => $faker->boolean ? $faker->secondaryAddress : $faker->buildingNumber,
        'address_line_1' => $faker->streetAddress,
        'town' => $faker->city,
        'county' => $faker->county,
        'postcode' => $faker->postcode,
        'date_created' => $created,
        'date_updated' => $created,
    ];
});
