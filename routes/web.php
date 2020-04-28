<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', static function () {
    $file ='https://thirdwrist-clothing.s3.eu-west-2.amazonaws.com/public/images/thread_posts/fri-apr-24-2020-200-pm1409517.jpeg';
    return Storage::disk('s3')->response($file);
});

