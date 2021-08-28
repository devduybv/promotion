<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Promotion\Entities\Promotion;

$factory->define(Promotion::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->userName,
        'title' => $faker->sentences(rand(5, 10), true),
        'type' => 'products',
        'start_date' => '1/1/2021',
        'status' => 1,
    ];
});
