<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker
                                                             $faker) {
    $status = ['ON', 'OFF'];
    return [
        'user_id' => rand(1,55),
        'product_id' => rand(1,10),
        'status' => $status[rand(0,1)]
    ];
});
