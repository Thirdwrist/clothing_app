<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ImageStorage;
use App\Http\Resources\ThreadCollection;
use App\Http\Resources\ThreadResource;
use App\Models\Post;
use App\Models\Thread;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends Controller
{
    use ImageStorage;

    public function index()
    {

        return $this->response($this->ok, new ThreadCollection(Thread::all()));
    }

    public function show(User $user, Thread $thread)
    {
        return response()->json([
            'status'=> $this->ok,
            'message'=> Response::$statusTexts[$this->ok],
            'data'=> [
                'thread'=> new ThreadResource($thread)
            ]
        ]);
    }

    public function store(User $user, Request $request)
    {
        $request->validate([
            'thread'=>['required', 'string', 'min:3'],
            'description'=> ['nullable', 'string', 'min:6'],
            'posts'=> ['required'],
            'posts.*.image'=>['required', 'image', 'max:15000'],
            'posts.*.description'=>['string']
        ]);


        $thread = Thread::create([
          'thread'=> $request->get('thread'),
          'description'=> $request->get('description'),
          'user_id'=> $user->id
        ]);

        $newThis = $this;
        collect($request->only('posts')['posts'])
            ->each(static function($post) use ($thread,$newThis){
            Post::create([
                'image_url' => $newThis->uploadImage($post['image']),
                'thread_id'=> $thread->id,
                'connection'=> config('filesystems.default'),
                'description'=> $post['description']
            ]);
        });

        return response()->json([
                'status' =>$this->created ,
                'message'=> Response::$statusTexts[$this->created],
                'data'=>[
                    'thread'=> new ThreadResource($thread)
                ]
            ], $this->created);
    }

    public function update(User $user, Thread $thread, Request $request)
    {
        $request->validate([
            'thread'=>['required', 'string', 'min:3'],
            'description'=> ['nullable', 'string', 'min:6'],
        ]);

        $thread->update($request->only(['thread', 'description']));

        return response()->json([
            'status'=> $this->ok,
            'message'=> Response::$statusTexts[$this->ok],
            'data'=>[
                 'thread'=> new ThreadResource($thread->refresh())
            ]
        ], $this->ok);
    }


}


