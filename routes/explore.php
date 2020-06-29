<?php

//Threads
use App\Http\Controllers\Explore\CollectionController;
use App\Http\Controllers\Explore\CollectionThreadController;
use App\Http\Controllers\Explore\PostController;
use App\Http\Controllers\Explore\SaveThreadController;
use App\Http\Controllers\Explore\TagController;
use App\Http\Controllers\Explore\TagThreadController;
use App\Http\Controllers\Explore\ThreadController;
use App\Http\Controllers\UserController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::middleware([])->group(static function(Router $router){

    $router->get('users/{user}/threads',             [UserController::class, 'threads'])->name('user.threads');
    $router->get('threads',                          [ThreadController::class, 'index'])->name('thread.index');
    $router->get('users/{user}/threads/{thread}',    [ThreadController::class, 'show'])->name('user.thread.show');
    $router->post('users/{user}/threads',            [ThreadController::class,'store'])->name('user.thread.store');
    $router->put('users/{user}/threads/{thread}',    [ThreadController::class, 'update'])->name('user.thread.update');

    //Posts in Thread
    $router->post('users/{user}/threads/{thread}/posts',                  [PostController::class, 'store'])->name('user.thread.post.store');
    $router->put('users/{user}/threads/{thread}/posts/{post}',            [PostController::class, 'update'])->name('user.thread.post.update');
    $router->delete('users/{user}/threads/{thread}/posts/{post}',         [PostController::class, 'destroy'])->name('user.thread.post.delete');

    // Tags in Thread
    $router->post('users/{user}/threads/{thread}/tags',              [TagThreadController::class, 'store'])->name('user.thread.tag.store');
    $router->delete('user/{user}/threads/{thread}/tags',            [TagThreadController::class, 'destroy'])->name('user.thread.tag.delete');

    //Saved threads
    $router->get('users/{user}/save/threads',                   [UserController::class, 'savedThreads'])->name('user.save.thread.index');
    $router->post('users/{user}/save/threads/{thread}',         [SaveThreadController::class, 'store'])->name('user.save.thread.store');
    $router->delete('users/{user}/save/threads/{thread}',       [SaveThreadController::class, 'destroy'])->name('user.save.thread.delete');

    //Tags
    $router->get('tags',                                        [TagController::class, 'index'])->name('tag.index');
    $router->post('tags',                                       [TagController::class, 'store'])->name('tag.store');
    $router->put('tags/{tag}',                                  [TagController::class, 'update'])->name('tag.update');

    $router->get('users/{user}/collections',                                    [UserController::class, 'collections'])->name('user.collections');
    $router->post('users/{user}/collections',                                   [CollectionController::class, 'store'])->name('user.collections.store');
    $router->put('users/{user}/collections/{collection}',                       [CollectionController::class, 'update'])->name('user.collections.update');
    $router->delete('users/{user}/collections/{collection}',                    [CollectionController::class, 'destroy'])->name('user.collections.delete');

    $router->post('users/{user}/collections/{collection}/threads',                          [CollectionThreadController::class, 'store'])->name('user.collections.threads.store');
    $router->delete('users/{user}/collections/{collection}/threads/{thread}',               [CollectionThreadController::class, 'destroy'])->name('user.collections.threads.delete');

});