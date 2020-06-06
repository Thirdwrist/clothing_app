<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Rules\MaximItem;
use App\User;
use function config;
use Illuminate\Http\Request;

class TagThreadController extends Controller
{
    public function store(User $user, Thread $thread, Request $request)
    {
        $request->validate([
            'tags'=> ['required', 'array'],
            'tags.*'=> ['required', 'exists:tags,id', new MaximItem($thread->tags(), config('data.max_tags_in_thread'), 'Tags')]
        ]);

        $thread->tags()->toggle(collect($request->get('tags'))->map(function ($tag){
            return ['model_type'=> Thread::class, 'tag_id'=> $tag];
        }));

        return $this->response($this->created);
    }

    public function destroy(User $user, Thread $thread, Request $request)
    {
        $request->validate([
            'tags'=> ['required', 'array'],
            'tags.*'=> ['required','exists:tags,id']
        ]);

        $thread->tags()->detach($request->get('tags'));

        return $this->response($this->ok);
    }
}
