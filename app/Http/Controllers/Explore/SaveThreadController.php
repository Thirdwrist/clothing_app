<?php

namespace App\Http\Controllers\Explore;

use App\Http\Resources\ThreadCollection;
use App\Models\Thread;
use App\User;
use Illuminate\Http\Request;
use App\Models\SavedModel;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class SaveThreadController extends Controller
{
    public  function __construct()
    {
        $this->middleware('auth.only.self')->only(['store', 'destroy']);
    }


    public function store(User $user, Thread $thread, Request $request)
    {
        if($user->savedThreads()->where('model_id', $thread->id)->count())
        {
            return $this->response(Response::HTTP_BAD_REQUEST);
        }
        SavedModel::create([
            'model_type'=> Thread::class,
            'model_id'=> $thread->id,
            'user_id'=>$user->id
        ]);

        return $this->response($this->created);
    }

    public function destroy(User $user, Thread $thread)
    {
        SavedModel::whereModelId($thread->id)->whereModelType(Thread::class)->delete();

        return $this->response($this->ok);
    }
}
