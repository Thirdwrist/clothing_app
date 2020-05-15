<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Thread;

class TagThreadController extends Controller
{
    public function store(Thread $thread, Tag $tag)
    {
        $thread->tags()->attach($tag);

        return $this->response($this->ok());
    }

    public function destroy(Thread $thread, Tag $tag)
    {
        $thread->tags()->detach($tag);

        return $this->response($this->ok());
    }
}
