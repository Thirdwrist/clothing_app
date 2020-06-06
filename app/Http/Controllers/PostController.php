<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ImageStorage;
use App\Http\Resources\ThreadResource;
use App\Models\Thread;
use App\Rules\MaximItem;
use App\User;
use function config;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    use ImageStorage;

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

        return response()->json([
            'status'=>$this->created,
            'message'=> Response::$statusTexts[$this->created],
            'data'=>[
                'thread'=> new ThreadResource($thread->refresh())
            ]
        ], $this->created);
    }

    public function update(User $user, Thread $thread, Post $post, Request $request)
    {
        $request->validate([
           'description'=> ['required', 'string'],
        ]);

       $post->update($request->only(['description']));

        return response()->json([
            'status'=> $this->ok,
            'message'=> Response::$statusTexts[$this->ok],
            'data'=>[
                'thread'=> new ThreadResource($post->refresh()->thread)
            ]
        ], $this->ok);
    }

    public function destroy(User $user, Thread $thread, Post $post)
    {
        $post->forceDelete();

        return response()->json([
            'status'=> $this->ok,
            'message'=> Response::$statusTexts[$this->ok],
        ], $this->ok);
    }
}
