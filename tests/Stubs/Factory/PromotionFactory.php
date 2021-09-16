<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Promotion\Entities\Promotion;
use VCComponent\Laravel\Promotion\Entities\Promotionable;

$factory->define(Promotion::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->userName,
        'title' => $faker->sentences(rand(5, 10), true),
        'type' => 'products',
        'start_date' => '1/1/2021',
        'status' => 1,
        'promo_type' => 1,
    ];
});
$factory->define(Promotionable::class, function (Faker $faker) {
    return [
        'promo_id' => 1,
        'promoable_type' => 'users',
        'promoable_id' => 1,
    ];
});
