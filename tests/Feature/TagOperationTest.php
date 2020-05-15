<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\Thread;
use App\User;
use function collect;
use function factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use function route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TagOperationTest extends TestCase
{
    /**
     * @test
     */
    public function creat_tag()
    {
        $tags = factory(Tag::class, 3)->raw();
        $actor= factory(User::class)->state('hashed_password')->create();
        $this->actingAs($actor)
            ->postJson(route('app.tag.store') , $tags)
            ->assertCreated()
            ->assertJson(
                [
                    'status'=> $status = Response::HTTP_CREATED,
                    'message'=> Response::$statusTexts[$status],
                ]);

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
            ->putJson(route('app.tag.update', ['tag'=>$tag]) , ['tag'=>$newTag])
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


    public function add_tags_to_thread()
    {
        $tags = factory(Tag::class, 3)->create();
        $actor= factory(User::class)->state('hashed_password')->create();
        $thread = factory(Thread::class, 1)->states(['posts', 'create'])->create();

        $this->actingAs($actor)
            ->postJson(route('app.thread.tag.store', ['thread'=>$thread->id]) , $tags->pluck('id')->toArray())
            ->assertOk()
            ->assertJson(
                [
                    'status'=> $status = Response::HTTP_OK,
                    'message'=> Response::$statusTexts[$status],
                ]);

        $tags->each(function ($tag){

            $this->assertDatabaseHas('model_has_tags', [
                'model'=>Thread::class,
                'model_id'=> $tag->id,
            ]);
        });

    }

    public function remove_tags_from_thread()
    {
        $tags = factory(Tag::class, 3)->create()->each(function($tag){
            $tag->threads()
                ->attach(factory(Thread::class)
                ->states(['create', 'posts'])->create()->id);
        });
        $thread = $tags->first()->threads->first();

        $this->actingAs($thread->user)
            ->postJson(route('app.thread.tag.delete', ['thread'=>$thread->id]) , $tags->pluck('id')->toArray())
            ->assertOk()
            ->assertJson(
                [
                    'status'=> $status = Response::HTTP_CREATED,
                    'message'=> Response::$statusTexts[$status],
                ]);


        $this->assertNull(
            $thread->tags()
            ->whereIn('model_id', $tags->pluck('id'))
            ->where('model_type', Thread::class)
        );

    }
}
