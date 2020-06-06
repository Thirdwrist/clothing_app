<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'collection'=>$faker->sentence,
        'user_id'=>factory(User::class)->create()->id,
        'description'=>$faker->sentence(30),
    ];
});
