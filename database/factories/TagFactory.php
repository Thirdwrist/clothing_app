<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Tag;
use App\User;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'tag'=> $faker->domainName,
        'user_id'=> factory(User::class)->state('hashed_password')->create()->id,
        'description'=> $faker->sentence(5),
    ];
});
