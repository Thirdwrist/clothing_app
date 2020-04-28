<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Models\Collection;
use App\Models\Thread;
use App\Models\CollectionHasThreads;

$factory->define(CollectionHasThreads::class, function (Faker $faker) {
    return [
        "collection_id"=> factory(Collection::class)->create()->id,
        'thread_id'=> factory(Thread::class)->create()->id
    ];
});
