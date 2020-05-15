<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SaveThreadController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TagThreadController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
use App\Models\Tag;
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

    $router->post('users/{user}/threads/{thread}/posts',                [PostController::class, 'store'])->name('user.thread.post.store');
    $router->put('users/{user}/threads/{thread}/posts/{post}',          [PostController::class, 'update'])->name('user.thread.post.update');
    $router->delete('users/{user}/threads/{thread}/posts/{post}',       [PostController::class, 'destroy'])->name('user.thread.post.delete');
    $router->post('user/{user}/threads/{thread}/tags/{tag}',              [TagThreadController::class, 'store'])->name('user.thread.tag.store');
    $router->delete('user/{user}/threads/{thread}/tags/{tag}',            [TagThreadController::class, 'destroy'])->name('user.thread.tag.destroy');

    $router->get('users/{user}/save/threads',                   [SaveThreadController::class, 'index'])->name('user.save.thread.index');
    $router->post('users/{user}/save/threads/{thread}',         [SaveThreadController::class, 'store'])->name('user.save.thread.store');
    $router->delete('users/{user}/save/threads/{thread}',       [SaveThreadController::class, 'destroy'])->name('user.save.thread.delete');

    $router->get('tags', [TagController::class, 'index'])->name('tag.index');
    $router->post('tags', [TagController::class, 'store'])->name('tag.store');
    $router->put('tags/{tag}', [TagController::class, 'update'])->name('tag.update');

});

