<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use Faker\Generator as Faker;
use App\Models\Thread;
use Illuminate\Http\UploadedFile;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'description'=> $faker->sentence(3),
    ];
});

$factory->state(Post::class, 'image', static function (){
    return [
        'image'=> UploadedFile::fake()->image('post.jpg'),
    ];
});

$factory->state(Post::class, 'create', function (Faker $faker){
    return [
        'image_url'=> $faker->imageUrl(),
        'thread_id'=> factory(Thread::class)->state('create'),
        'connection'=> env('FILESYSTEM_DRIVER')
    ];
});
