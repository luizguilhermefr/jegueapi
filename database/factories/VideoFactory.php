<?php

use App\Category;
use App\User;
use App\Video;

$factory->define(Video::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->name,
        'description' => $faker->sentence(),
        'playable' => $faker->url,
        'owner' => User::inRandomOrder()
            ->first()->username,
        'category_id' => Category::inRandomOrder()
            ->first()->id,
    ];
});
