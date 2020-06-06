<?php

namespace Tests\Feature;

use App\Http\Resources\ThreadResource;
use App\Models\Post;
use App\Models\Thread;
use App\User;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class ThreadOperationTest extends TestCase
{
    /**
     * @test
     * */
    public function create_thread()
    {
        $this->withoutExceptionHandling();
        $thread = factory(Thread::class)->state('posts')->raw();
        $user = factory(User::class)->create();

        $res = $this->actingAs($user)
            ->postJson(route('app.user.thread.store', ['user'=>$user->id]),$thread);
        $threadCreated = Thread::where([
            'thread'=>$thread['thread'],
            'description'=>$thread['description']
        ])->orderBy('created_at', 'DESC')->first();

        $json = (new ThreadResource($threadCreated))->toJson();
        $res->assertStatus(201)
            ->assertJson([
                'status'=> $status = Response::HTTP_CREATED,
                'message'=> Response::$statusTexts[$status],
                'data'=>[
                    'thread'=> json_decode($json, true)
                ]
            ]);

        $this->assertDatabaseHas('threads',
            ['thread'=> $thread['thread'], 'description'=> $thread['description']]
        );

        collect($thread['posts'])->each(function ($post){
            $this->assertDatabaseHas('posts', [
                'description'=> $post['description'],
            ]);
        });


        //Always local storage on testing environment
        $st = Storage::disk(env('FILESYSTEM_DRIVER'))
                ->exists($threadCreated->posts()->first()->image_url);
        $this->assertTrue($st);
    }

    /**
     * @test
     * */
    public function validation_for_thread_creation()
    {

        $thread = factory(Thread::class)->raw([
            'thread'=>'o'
        ]);

        $threadCreated = factory(Thread::class)->states(['create'])->create();

        $this->actingAs($user = factory(User::class)->state('hashed_password')->create())
            ->putJson(route('app.user.thread.update',
                [
                    'user'=>$user->id,
                    'thread'=>$threadCreated->id
                ]), $thread)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['thread']);


        $this->assertDatabaseMissing('threads',
            [
                'description'=> $thread['description'],
                'user_id'=> $user->id
            ]
        );
    }

    /**
     * @test
     * */
    public function add_post_to_thread()
    {
        $this->withoutExceptionHandling();
        $thread = factory(Thread::class)->state('create')->create();
        $posts = factory(Post::class)->state('image')->raw();
        $json = (new ThreadResource($thread))->toJson();
        $res = $this->actingAs($user = $thread->user)
            ->postJson(route('app.user.thread.post.store', ['user'=>$user->id, 'thread'=>$thread->id]), $posts);

            $res->assertStatus(201)
            ->assertJson([
                'status'=>$status = Response::HTTP_CREATED,
                'message'=> Response::$statusTexts[$status],
                'data'=>[
                    'thread'=> json_decode($json, true)
                ]
            ]);

            $this->assertDatabaseHas('posts', [
                'description'=> $posts['description'],
                'thread_id'=> $thread->id
            ]);

        //Always local storage on testing environment
        $st = Storage::disk(env('FILESYSTEM_DRIVER'))
            ->exists($img = $thread->refresh()->posts->last()->image_url);
        $this->assertTrue($st);
    }

    /**
     * @test
     * */
    public function edit_post_in_thread()
    {
        $this->withoutExceptionHandling();
        $thread = factory(Thread::class)->state('create')->create();
        $post = $thread->posts()->first();
        $this->actingAs($thread->user)
            ->putJson(
                route('app.user.thread.post.update', [
                    'thread'=>$thread->id,
                    'user'=>$thread->user->id,
                    'post'=>$post->id
                ]),
            [
                'description'=> $description = Factory::create()->sentence,
            ])
            ->assertStatus(200);


        $this->assertDatabaseHas('posts', [
            'description'=> $description,
            'thread_id'=> $thread->id,
        ]);
    }
    /**
     * @test
     * */
    public function edit_thread()
    {
        $thread = factory(Thread::class)->state('create')->create();
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->putJson(route('app.user.thread.update', ['thread'=> $thread->id, 'user'=> $thread->user->id]), [
            'thread'=> $threadTitle = Factory::create()->sentence,
            'description'=> $description = Factory::create()->sentence(2)
        ])->assertOk();

        $this->assertDatabaseHas('threads', [
            'description'=> $description,
            'thread'=> $threadTitle,
            'id'=>$thread->id
        ]);

    }

    /**
     * @test
     * */
    public function delete_post()
    {
        $post = factory(Post::class)->state('create')->create();
        $this->actingAs($post->thread->user)
            ->deleteJson(route('app.user.thread.post.delete', [
            'thread'=>$post->thread_id,
            'user'=>$post->thread->user->id,
            'post'=> $post->id
        ])
        )->assertOk();

        $this->assertNull(Post::find($post->id));
    }


    public function delete_thread()
    {
        $this->withoutExceptionHandling();
        $post = factory(Post::class)->state('create')->create();
        $thread = $post->thread;
        $this->deleteJson(route('app.user.thread.post.delete', [
                'thread'=> $thread->id,
                'user'=>$thread->user->id,
                'post'=>$post->id
        ]))->assertOk();

        $this->assertNull(Thread::find($thread->id));
    }

    public function see_threads_for_a_user()
    {

    }
}
