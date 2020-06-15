<?php

namespace App\Http\Controllers;


use App\Http\Resources\SlimCollectionResource;
use App\Http\Resources\ThreadCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.only.self')->only('update');
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'name'=> ['min:2'],
            'username'=> ['min:3', 'alpha_dash', 'unique:users,username,'.auth()->id()],
            'gender'=> [Rule::in([User::FEMALE, User::MALE])],
            'role'=> [Rule::in([User::BUSINESS, User::DESIGNER, User::USER])],
            'email'=> ['email', 'unique:users,email,'.auth()->id()],
            'nationality'=> [Rule::in(array_keys(config('data.country_codes')))],
            'password'=> ['min:8']
        ]);

        $user->update($request->only([
            'name', 'username', 'gender', 'role', 'email', 'nationality'
        ]));

        if($request->get('password'))
        {
            $user->update(['password'=> Hash::make($request->get('password'))]);

        }

        return $this->response($this->ok, ['user'=> $user->refresh()]);
    }

    public function threads(User $user)
    {
        return $this->response(Response::HTTP_OK, new ThreadCollection($user->threads));
    }

    public function savedThreads(User $user)
    {
        $savedThreads = $user->savedThreads;

        return $this->response($this->ok,['models'=> new ThreadCollection($savedThreads)]);
    }

    public function collections(User $user)
    {
        $collections = $user->collections;

        return $this->response($this->ok, [
            'collections'=> SlimCollectionResource::collection($collections)
        ]);
    }
}
