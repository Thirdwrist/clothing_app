<?php

namespace App\Http\Controllers\Explore;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::select(['id','tag'])->get();

        return $this->response($this->ok, [
            'tags'=> $tags
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tags'=>['required', 'array'],
            'tags.*.tag'=>['required','string', 'min:3'],
            'tags.*.description'=>['nullable','string', 'min:3']
        ]);

        collect($request->get('tags'))->each(function($tag){
            Tag::create([
                'tag'=>$tag['tag'],
                'description'=> $tag['description'],
                'user_id'=>auth()->id()
            ]);
        });

        return $this->response($this->created);
    }

    public function update(Tag $tag, Request $request)
    {
        $request->validate([
            'tag'=>['required', 'min:2'],
            'description'=>['nullable', 'min:5'],
        ]);

        $tag->update($request->only(['tag', 'description']));

        return $this->response($this->ok);
    }
}
