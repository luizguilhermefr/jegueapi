<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password = 'secret';

    return [
        'username' => $faker->userName,
        'password' => hash('sha256', $password),
        'description' => $faker->name,
        'email' => $faker->email,
        'remember_token' => $faker->sha256
    ];
});
