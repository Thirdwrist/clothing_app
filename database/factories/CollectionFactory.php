<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Collection as CollectionAlias;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(CollectionAlias::class, function (Faker $faker) {
    return [
        'collection'=>$name = $faker->sentence,
        'slug'=>Str::slug($name, '_'),
        'user_id'=>factory(User::class)->create()->id,
        'description'=>$faker->sentence(30),
    ];
});
