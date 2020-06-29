<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message'=>trans('auth.failed')
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }

    /*
     * Initial registration process
     * */
    public  function register(Request $request)
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

        $user = $this->createUser($request);
        $token = auth()->attempt(['email'=> $user->email, 'password'=> $request->get('password')]);
        return response()->json([
            'status'=> $this->created,
            'message'=> Response::$statusTexts[$this->created],
            'data'=> [
                'auth'=> [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => $this->guard()->factory()->getTTL() * 60
                ],
                'user'=> $user,
            ]
        ], $this->created);
    }

    private function createUser(Request $request)
    {
        return User::create([
           'username'=> $request->get('username'),
           'email'=> $request->get('email'),
           'name'=> $request->get('name'),
            'gender'=> $request->get('gender'),
            'role'=> $request->get('role'),
            'nationality'=> $request->get('nationality'),
            'password'=> Hash::make($request->get('password'))
        ])->refresh();
    }

}
