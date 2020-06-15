<?php

namespace App\Http\Controllers;

use App\Http\Resources\CollectionResource;
use App\Http\Resources\SlimCollectionResource;
use App\Models\Collection;
use App\Models\Thread;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.only.self')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        $allCollections = Collection::all();

       return $this->response($this->ok, [
           'collections'=> SlimCollectionResource::collection($allCollections)
       ]);
    }

    public function show(Collection $collection)
    {
        $this->response($this->ok ,[
            'collection'=> new CollectionResource($collection)
        ]);
    }

    public function store(User $user, Request $request)
    {

        $request->validate([
            'collection'=> ['required', 'string'],
            'description'=> ['nullable', 'string'],
            'thread_ids'=> ['array'],
            'thread_ids*'=> ['exists:threads,id']
        ]);

        $collection  = Collection::create([
            'collection'=> $name = $request->get('collection'),
            'user_id'=>$user->id,
            'slug'=> $this->create_slug($name, $user),
            'description'=>$request->get('description'),
        ]);

        collect($request->get('thread_ids'))->each(function ($thread) use ($collection){
            $collection->threads()->save(Thread::find($thread), ['user_id'=>$collection->user->id]);
        });

        return $this->response($this->created, ['collection'=> new SlimCollectionResource($collection)]);
    }

    public function update(User $user, Collection $collection, Request $request)
    {
        Gate::authorize('update', $collection);

        $request->validate([
            'collection'=> ['required', 'string'],
            'description'=> ['string']
        ]);

        if($name = $request->get('collection'))
        {
          $collection->update([
              'slug'=>$this->create_slug($name, $user)
          ]);
        }
        $collection->update($request->only(['collection', 'description']));

        return $this->response($this->ok);
    }

    public function destroy(User $user, Collection $collection, Request $request)
    {
        Gate::authorize('destroy', $collection);

        $collection->threads->each(function($thread) use ($collection, $user){
            $collection->refresh()->threads()->detach($thread->id, ['user_id'=> $user->id]);
        });

        $collection->delete();

        return $this->response($this->ok);
    }

    private function create_slug($collection, User $user)
    {
        $slug = Str::slug($collection, '_');
        while (Collection::whereUserId($user->id)->whereSlug($slug)->first())
        {
            $slug = Str::slug($collection, '_').'_'.Str::random(5);
        }
        return $slug;
    }
}
