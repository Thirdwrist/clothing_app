<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route for all Authentication
Route::middleware('api')->namespace('Auth')->group(static function (Router $router) {

    $router->post('login',      [AuthController::class, 'login'])->name('login');
    $router->post('register',   [AuthController::class, 'register'])->name('register');
    $router->post('logout',     [AuthController::class, 'logout']);
    $router->post('refresh',    [AuthController::class, 'refresh']);
    $router->post('me',         [AuthController::class, 'me']);

});


Route::name('app.')->middleware(['auth:api'])->group(static function(Router $router){

    $router->put('users/{user}',                     [UserController::class, 'update'])->name('user.update');

    // Explore Module Routes
    include 'explore.php';

});

