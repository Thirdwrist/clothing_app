<?php

namespace App\Http\Controllers\Explore;

use App\Http\Controllers\Concerns\ImageStorage;
use App\Http\Resources\ThreadCollection;
use App\Http\Resources\ThreadResource;
use App\Models\Post;
use App\Models\Thread;
use App\User;
use Illuminate\Http\Request;
Use App\Http\Controllers\Controller;

class ThreadController extends Controller
{
    use ImageStorage;

    public function __construct()
    {
        $this->middleware('auth.only.self')->only(['store', 'update']);
    }

    public function index()
    {

        return $this->response($this->ok, new ThreadCollection(Thread::all()));
    }

    public function show(User $user, Thread $thread)
    {
        $thread = $user->threads()->where('id', $thread->id)->firstOrFail();

        return $this->response($this->ok, ['thread'=> new ThreadResource($thread)]);
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

        return $this->response($this->created, ['thread'=> new ThreadResource($thread)]);
    }

    public function update(User $user, Thread $thread, Request $request)
    {
        $request->validate([
            'thread'=>['required', 'string', 'min:3'],
            'description'=> ['nullable', 'string', 'min:6'],
        ]);

        $thread->update($request->only(['thread', 'description']));

        return $this->response($this->ok, ['thread'=> new ThreadResource($thread->refresh())]);
    }


}


