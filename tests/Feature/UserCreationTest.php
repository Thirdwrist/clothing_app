<?php

namespace Tests\Feature;

use App\Http\Controllers\Concerns\HttpResponses;
use function auth;
use function collect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserCreationTest extends TestCase
{
    use HttpResponses;

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function create_user_account()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->raw([
            'nationality'=> 'NG'
        ]);

        $res = $this->postJson(route('register'), $user)
            ->assertStatus(201);

        $res->assertJson($this->responseInArray($this->created()));

    }

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function fail_validation_on_duplicate_email_and_username()
    {
        $user = factory(User::class)->create();
        $user = factory(User::class)->raw( [
            'email'=> $user->email,
            'username'=> $user->username
        ]);

        $this->postJson(route('register'), $user)
            ->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'name'=> $user['name']
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function update_user_account()
    {
        $user = factory(User::class)->create();
        $updateUser = factory(User::class)->raw();
        $this->actingAs($user)
            ->putJson(route('app.user.update', ['user'=>$user]) ,$updateUser)
            ->assertOk()
            ->assertJson(
                [
                    'status'=> $status = Response::HTTP_OK,
                    'message'=> Response::$statusTexts[$status],
                    'data'=> [
                        'user'=> Arr::except($updateUser, 'password')
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'username'=>$updateUser['username'],
            'email'=> $updateUser['email'],
            'name'=> $updateUser['name']
        ]);
    }
}
