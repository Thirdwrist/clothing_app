<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $genders = [User::MALE, User::FEMALE];
    $roles = [User::BUSINESS, User::DESIGNER, User::USER];
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'username'=> $faker->firstName,
        'gender'=> Arr::random($genders),
        'role'=> Arr::random($roles),
        'nationality'=>Arr::random(array_keys(config('data.country_codes'))),
        'password' => 1234567890,
    ];
});

$factory->state(User::class, 'hashed_password', [
    'password' => Hash::make(1234567890)
]);

$factory->state(User::class, 'create', [
    'password' => Hash::make(1234567890)
]);
