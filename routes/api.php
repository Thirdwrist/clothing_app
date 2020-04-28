<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ThreadController;
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

    $router->put('user/{user}',                     [UserController::class, 'update'])->name('user.update');

    $router->get('user/{user}/threads',             [UserController::class, 'threads'])->name('user.threads');
    $router->get('threads',                         [ThreadController::class, 'index'])->name('thread.index');
    $router->get('user/{user}/threads/{thread}',    [ThreadController::class, 'show'])->name('user.thread.show');
    $router->post('user/{user}/threads',            [ThreadController::class,'store'])->name('user.thread.store');
    $router->put('user/{user}/threads/{thread}',    [ThreadController::class, 'update'])->name('user.thread.update');

    $router->post('users/{user}/threads/{thread}/posts', [              PostController::class, 'store'])->name('user.thread.post.store');
    $router->put('users/{user}/threads/{thread}/posts/{post}',          [PostController::class, 'update'])->name('user.thread.post.update');
    $router->delete('users/{user}/threads/{thread}/posts/{post}',       [PostController::class, 'destroy'])->name('user.thread.post.delete');

});

