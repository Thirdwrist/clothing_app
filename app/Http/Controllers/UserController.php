<?php

namespace App\Http\Controllers;


use App\Http\Resources\ThreadCollection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
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
            'name', 'username', 'gender', 'role', 'email', 'nationality','password'
        ]));

        return response()->json([
            'status'=> $this->ok,
            'message'=> Response::$statusTexts[$this->ok],
            'data'=> [
                'user'=> $user
            ]
        ], $this->ok);
    }

    public function threads(User $user)
    {
        return $this->response(Response::HTTP_OK, new ThreadCollection($user->threads));
    }
}
