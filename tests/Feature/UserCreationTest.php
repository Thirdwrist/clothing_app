<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\User;

class UserCreationTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;
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

        $this->postJson(route('app.user.create'), $user)
            ->assertStatus(201)
            ->assertJson(
                [
                    'status'=> $status = Response::HTTP_CREATED,
                    'message'=> Response::$statusTexts[$status],
                    'data'=> [
                        'user'=> Arr::except($user, 'password')
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'username'=>$user['username'],
            'email'=> $user['email'],
            'name'=> $user['name']
        ]);
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

        $this->postJson(route('app.user.create'), $user)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'email']);

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
        $this->putJson(route('app.user.update', ["user"=>$user]) ,$updateUser)
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
