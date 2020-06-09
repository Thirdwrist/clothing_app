<?php

namespace Tests\Feature;

use App\Http\Controllers\Concerns\HttpResponses;
use App\Models\Collection;
use App\Models\Thread;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionOperationTest extends TestCase
{
    use HttpResponses, DatabaseMigrations, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @test void
     */
    public function create_collection()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->state('hashed_password')->create();
        $create = $this->actingAs($user)
            ->post(
                route('app.user.collections.store', ['user'=> $user->id]),
                $collection =  factory(Collection::class)->raw()
            );
        $create->assertCreated();
        $create->assertJson($this->responseInArray($this->created()));

        $this->assertDatabaseHas('collections', [
            'collection'=> $collection['collection'],
            'description'=> $collection['description'],
            'user_id'=> $user->id
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @test void
     */
    public function add_thread_to_collection()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->state('create')->create();
        $user->collections()
                ->save(factory(Collection::class)->make());

        $collection = $user->collections()->first();
        $thread = factory(Thread::class)->state('create')->create();

        $update = $this->actingAs($user)
            ->post(route('app.user.collections.threads.store', ['user'=>$user->id,'collection'=>$collection->id]), [
                'thread_id' => $thread->id
            ]);
        $update->assertCreated();
        $update->assertJson($this->responseInArray($this->created()));

        $this->assertDatabaseHas('collections_has_threads', [
            'collection_id'=> $collection->id,
            'thread_id'=> $thread->id,
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @test void
     */
    public function remove_thread_from_Collection()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->state('create')->create();
        $collection = $user->collections()
            ->save(factory(Collection::class)->make());

        $thread = factory(Thread::class)->state('create')
            ->create();

        $user->collections()
            ->get()
            ->first()
            ->threads()
            ->attach($thread->id, ['user_id'=> $user->id]);


        $remove = $this->actingAs($user)
            ->deleteJson(route('app.user.collections.threads.delete', [
                'collection'=> $collection->id,
                'thread'=> $thread->id,
                'user'=>$user->id
            ]));
        $remove->assertOk();

        $remove->assertJson($this->responseInArray($this->ok()));

        $this->assertDatabaseMissing('collections_has_threads', [
           'collection_id'=> $collection->id,
           'thread_id'=> $thread->id
        ]);

    }

    /**
     * A basic feature test example.
     *
     * @test void
     */
    public function update_collection()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->state('create')->create();
        $collection = $user->collections()
            ->save(factory(Collection::class)->make());

        $update = $this->actingAs($user)
            ->putJson(route('app.user.collections.update', [
            'collection'=> $collection->id,
            'user'=> $user
        ]), [
            'collection'=> $name = Factory::create()->text,
            'description'=> $description = Factory::create()->sentence(45)
        ]);

        $update->assertOk();

        $this->assertDatabaseHas('collections', [
           'collection'=>  $name,
            'description'=> $description,
            'id'=> $collection->id
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @test void
     */
    public function delete_collection()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->state('create')->create();
        $collection = $user->collections()
            ->save(factory(Collection::class)->make());

        $delete = $this->actingAs($user)->deleteJson(route('app.user.collections.delete', [
            'collection'=> $collection->id,
            'user'=> $user->id
        ]));

        $delete->assertOk();

        $this->assertDatabaseMissing('collections', [
            'id'=> $collection->id
        ]);
    }
}
