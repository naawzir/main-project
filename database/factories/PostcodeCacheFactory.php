<?php

use Faker\Generator as Faker;

$factory->define(App\Cache::class, function (Faker $faker) {

    $postcode = $faker->unique()->postcode;
    $key = 'address-lookup-'
        . str_replace(' ', '', strtoupper($postcode));

    $addresses = [];
    $postcodeParts = explode(' ', $postcode);

    $number = $faker->buildingNumber;

    foreach (range(1, random_int(10, 100)) as $index) {
        $address = [
            'postcode' => $postcode,
            'postcode_inward' => $postcodeParts[0],
            'postcode_outward' => $postcodeParts[1],
            'post_town' => $faker->city,
            'dependant_locality' => 'NOT ABLE TO FAKE',
            'double_dependant_locality' => 'NOT ABLE TO FAKE',
            'thoroughfare' => $faker->streetName,
            'dependant_thoroughfare' => $faker->optional()->streetName,
            'building_number' => $number,
            'building_name' => 'NOT ABLE TO FAKE',
            'sub_building_name' => 'NOT ABLE TO FAKE',
            'po_box' => $faker->optional()->numberBetween(100, 999),
            'department_name' => 'NOT ABLE TO FAKE',
            'organisation_name' => $faker->optional(0.1)->company,
            'udprn' => 'NOT ABLE TO FAKE',
            'umprn' => 'NOT ABLE FO FAKE',
            'postcode_type' => 'S',
            'su_organisation_indicator' => 'NOT ABLE TO FAKE',
            'delivery_point_suffix' => $number,
            'line_1' => $number . ' ' . $faker->streetAddress,
            'line_2' => 'NOT ABLE TO FAKE',
            'line_3' => 'NOT ABLE TO FAKE',
            'premise' => $faker->numberBetween(0,1),
            'longitude' => $faker->longitude,
            'latitude' => $faker->latitude,
            'eastings' => 'NOT ABLE TO FAKE',
            'northings' => 'NOT ABLE TO FAKE',
            'country' => 'England',
            'traditional_county' => $faker->county,
            'administrative_county' => '',
            'postal_county' => $faker->county,
            'county' => $faker->county,
            'district' => 'NOT ABLE TO FAKE',
            'ward' => 'NOT ABLE TO FAKE',
        ];
        $addresses[] = $address;
    }
    $expires = $faker->dateTimeBetween('now', '+1 year')->getTimestamp();
    return [
        'key' => $key,
        'value' => json_encode($addresses),
        'expiration' => $expires,
        //'date_updated' => $created,
    ];
});
