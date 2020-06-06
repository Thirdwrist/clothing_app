<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Tag;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'thread_id'=> factory(Thread::class)->create()->id,
        'tag_id'=> factory(Tag::class)->create()->id
    ];
});
