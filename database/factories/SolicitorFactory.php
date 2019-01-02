<?php

use Faker\Generator as Faker;

$factory->define(App\Solicitor::class, function (Faker $faker) {

    $created = $faker->dateTimeThisYear->getTimestamp();

    return [
        'name' => $faker->company,
        'slug' => $faker->uuid,
        'date_created' => $created,
        'date_updated' => $created,
        'default_office' => null,
        'status' => 'Active',
    ];
});
