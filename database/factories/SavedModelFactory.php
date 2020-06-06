<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SavedModel;
use App\User;
use Faker\Generator as Faker;
use App\Models\Thread;

$factory->define(SavedModel::class, static function (Faker $faker) {
    return [
    ];
});

$factory->state(SavedModel::class, 'thread', static function (Faker $faker){
   return [
       'model_type'=> Thread::class,
       'model_id'=> factory(Thread::class)->state('create'),
       'user_id'=> factory(User::class)->state('hashed_password')
   ];
});
