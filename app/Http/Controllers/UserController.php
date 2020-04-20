<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'=> ['required'],
            'username'=> ['required', 'alpha_dash', 'unique:users,username'],
            'gender'=> ['required', Rule::in([User::FEMALE, User::MALE])],
            'role'=> ['required', Rule::in([User::BUSINESS, User::DESIGNER, User::USER])],
            'email'=> ['required', 'unique:users,email'],
            'nationality'=> ['required', Rule::in(array_keys(config('data.country_codes')))],
            'password'=> ['required', 'min:8']
        ]);

        $user = User::create($request->only([
            'name', 'username', 'gender', 'role', 'email', 'nationality','password'
        ]));

        return response()->json([
            'status'=> $status = Response::HTTP_CREATED,
            'message'=> Response::$statusTexts[$status],
            'data'=> [
                'user'=> $user
            ]
        ], 201);
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
            'name', 'username', 'gender', 'role', 'email', 'nationality','password'
        ]));

        return response()->json([
            'status'=> $status = Response::HTTP_OK,
            'message'=> Response::$statusTexts[$status],
            'data'=> [
                'user'=> $user
            ]
        ], 200);
    }
}
