<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ImageStorage;
use App\Http\Resources\ThreadResource;
use App\Models\Thread;
use App\Rules\MaximItem;
use App\User;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    use ImageStorage;

    public function __construct()
    {
        $this->middleware('auth.only.self');
    }

    public function store(User $user, Thread $thread, Request $request)
    {

        $request->validate([
            'image'=>['required', 'image', 'max:15000'],
            'description'=>['string', new MaximItem($thread->posts(), config('data.max_posts_in_thread'), 'posts')]
        ]);



        Post::create([
            'image_url'=> $this->uploadImage($request->file('image')),
            'description'=>$request->get('description'),
            'connection'=> config('filesystems.default'),
            'thread_id'=> $thread->id
        ]);

        return $this->response($this->created, ['thread'=> new ThreadResource($thread->refresh())]);
    }

    public function update(User $user, Thread $thread, Post $post, Request $request)
    {
        $request->validate([
           'description'=> ['required', 'string'],
        ]);

       $post->update($request->only(['description']));

        return $this->response($this->ok, [
            'thread'=> new ThreadResource($post->refresh()->thread)
        ]);
    }

    public function destroy(User $user, Thread $thread, Post $post)
    {
        $post->forceDelete();

        return  $this->response($this->ok);
    }
}
