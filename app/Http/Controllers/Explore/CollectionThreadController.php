<?php

namespace App\Http\Controllers\Explore;

use App\Models\Collection;
use App\Models\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;


class CollectionThreadController extends Controller
{
    public function store(User $user, Collection $collection, Request $request)
    {
        Gate::authorize('addThread', $collection);
        $request->validate([
            'thread_id'=> ['required', 'exists:threads,id']
        ]);

        $collection->threads()
            ->attach($request->get('thread_id'), ['user_id'=> $user->id]);

        return $this->response($this->created);
    }

    public function destroy(User $user, Collection $collection, Thread $thread)
    {
        Gate::authorize('removeThread', $collection);
        $collection->threads()
            ->detach([
                'user_id'=> $user->id,
                'thread_id'=> $thread->id
            ]);

        return $this->response($this->ok);
    }
}
