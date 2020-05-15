<?php

namespace Tests\Feature;

use App\Models\SavedModel;
use App\Models\Thread;
use App\User;
use Tests\TestCase;

class SaveThreadOperationTest extends TestCase
{
    /**
     *
     * @test
     */
    public function save_a_thread()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->state('hashed_password')->create();
        $thread = factory(Thread::class)->state('create')->create();

        $this->actingAs($user)
            ->postJson(route('app.user.save.thread.store', ['user'=>$user->id, 'thread'=>$thread->id]))
            ->assertCreated();

        $this->assertDatabaseHas('saved_models', [
            'model_type'=>Thread::class,
            'model_id'=> $thread->id,
            'user_id'=> $user->id
        ]);
    }

    /**
     *
     * @test
     */
    public function discard_a_saved_thread()
    {
        $savedThread = factory(SavedModel::class)->state('thread')->create();

        $this->actingAs($savedThread->user)
            ->deleteJson(route('app.user.save.thread.delete', ['user'=>$savedThread->user->id, 'thread'=>$savedThread->model->id]), [
                'thread_id'=> $savedThread->model->id
            ])
            ->assertOk();

        $this->assertNull(Thread::where([
            'user_id'=> $savedThread->user->id,
            'model_type'=> Thread::class,
            'model_id'=> $savedThread->id
        ])->first());
    }
}
