<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\User;
use Illuminate\Http\Request;
use App\Models\SavedModel;

class SaveThreadController extends Controller
{

    public function store(User $user, Thread $thread, Request $request)
    {
        SavedModel::create([
            'model_type'=> Thread::class,
            'model_id'=> $thread->id,
            'user_id'=>$user->id
        ]);

        return $this->response($this->created);
    }

    public function destroy(User $user, Thread $thread)
    {
        SavedModel::whereModelId($thread->id)->delete();

        return $this->response($this->ok);
    }
}
