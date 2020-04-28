<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use App\Models\Thread;
use App\Models\Post;

$factory->define(Thread::class, function (Faker $faker) {
    return [
        'thread'=> $faker->sentence,
        'description'=> $faker->sentence(20),
    ];
});

$factory->state(Thread::class, 'create', function (Faker $faker){
    return  [
        'user_id'=> factory(User::class),
    ];
});

$factory->state(Thread::class, 'posts', function (){
   return [
       'posts'=> factory(Post::class, 2)->state('image')->raw(),
   ] ;
});


$factory->afterCreating(Thread::class, static function ($thread, $faker){
    factory(Post::class, 2)->states(['create'])->create(['thread_id'=>$thread->id]);
});

