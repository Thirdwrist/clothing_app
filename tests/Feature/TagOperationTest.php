<?php

namespace Tests\Feature;

use App\Http\Controllers\Concerns\HttpResponses;
use App\Models\Tag;
use App\Models\Thread;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TagOperationTest extends TestCase
{
    use HttpResponses;
    /**
     * @test
     */
    public function create_tag()
    {
        $tags = factory(Tag::class, 3)->raw();
        $actor= factory(User::class)->state('hashed_password')->create();
        $this->actingAs($actor)
            ->postJson(route('app.tag.store') , ['tags'=>$tags])
            ->assertCreated()
            ->assertJson($this->responseInArray($this->created()));

        collect($tags)->each(function ($tag) use($actor){
            $this->assertDatabaseHas('tags', [
                'tag'=>$tag['tag'],
                'description'=> $tag['description'],
                'user_id'=> $actor->id
            ]);
        });
    }
    /**
     * @test
     */
    public function edit_tag()
    {
        $tag = factory(Tag::class)->create();
        $newTag = factory(Tag::class)->raw();
        $actor= factory(User::class)->state('hashed_password')->create();
        $this->actingAs($actor)
            ->putJson(route('app.tag.update', ['tag'=>$tag]) , $newTag)
            ->assertOk()
            ->assertJson(
                [
                    'status'=> $status = Response::HTTP_OK,
                    'message'=> Response::$statusTexts[$status],
                ]);

        $this->assertDatabaseHas('tags', [
            'tag'=>$newTag['tag'],
            'description'=> $newTag['description'],
        ]);
    }

    /**
     * @test
     */
    public function attach_tags_to_thread()
    {
        $this->withoutExceptionHandling();
        $tags = factory(Tag::class,3)->create();
        $thread = factory(Thread::class)->state('create')->create();
        $this->actingAs($thread->user)
            ->postJson(route('app.user.thread.tag.store', [
                'thread'=>$thread->id,
                'user'=>$thread->user->id,
                ]
            ) , ['tags'=>$tags->pluck('id')->toArray()])
            ->assertCreated()
            ->assertJson(
                [
                    'status'=> $status = Response::HTTP_CREATED,
                    'message'=> Response::$statusTexts[$status],
                ]);

        $tags->each(function ($tag) use($thread){

            $this->assertDatabaseHas('model_has_tags', [
                'model_type'=>Thread::class,
                'model_id'=> $thread->id,
                'tag_id'=> $tag->id,
            ]);
        });

    }
    /**
     * @test
     */
    public function detach_tags_from_thread()
    {
        $this->withoutExceptionHandling();
        $thread = factory(Thread::class)->state('create')->create();
        $tags = factory(Tag::class, 3)->create()->each(function($tag) use ($thread){
            $tag->threads()
                ->attach($thread->id, ['model_type'=> Thread::class]);
        });

        factory(Tag::class)
            ->create()
            ->threads()
            ->attach($thread->id, ['model_type'=> 'rr']);
        $this->actingAs($thread->user)
            ->deleteJson(
                route('app.user.thread.tag.delete', ['user'=>$thread->user->id,'thread'=>$thread->id]),
                ['tags'=> $tags->pluck('id')->toArray()]
            )
            ->assertOk()
            ->assertJson(
                [
                    'status'=> $status = Response::HTTP_OK,
                    'message'=> Response::$statusTexts[$status],
                ]);

        $tags->each(function ($tag) use($thread){
            $this->assertNull($thread->tags()->where([
                'model_type'=>Thread::class,
                'model_id'=> $thread->id,
                'tag_id'=> $tag->id,
            ])->first());
        });

        $this->assertDatabaseHas('model_has_tags', [
            'model_type'=>'rr',
            'model_id'=> $thread->id,
        ]);

    }
}
