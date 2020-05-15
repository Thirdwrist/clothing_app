<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;


class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();

        return $this->response($this->ok, [
            'tags'=> $tags->pluck('tag')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tag'=>['required'],
            'tag.*'=>['array','string']
        ]);

        Tag::create($request->only(['tag', 'description']));

        return $this->response($this->created);
    }

    public function update(Tag $tag, Request $request)
    {
        $request->validate([
            'tag'=>['required'],
            'tag.*'=>['array','string']
        ]);

        $tag->update($request->only(['tag', 'description']));

        return $this->response($this->ok);
    }
}
