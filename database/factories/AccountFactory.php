<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Account::class, function (Faker $faker) {
    $now = now();

    return [
        'area_code' => '0086',
        'phone' => $faker->unique()->phoneNumber,
        'name' => $faker->firstName.' '.$faker->lastName,
        'email' => $faker->unique()->email,
        'status' => $faker->randomElement(array_keys(\App\Models\Account::$statusMap)),
        'login_times' => $faker->numberBetween(1, 99),
        'last_login_ip' => $faker->ipv4,
        'created_with_ip' => $faker->ipv4,
        'password' => bcrypt('123123'),
        'pay_password' => bcrypt('123123'),
        'last_login_at' => $now,
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
