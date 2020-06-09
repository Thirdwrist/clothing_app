<?php

use App\Models\Post;
use App\Models\Thread as ThreadAlias;
use App\User;
use Illuminate\Database\Seeder;
use App\Models\Collection;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class, 4)
            ->state('create')
            ->create() // Create users
            ->each(static function($user){
                $user->threads()->createMany(
                    factory(ThreadAlias::class, 5)->raw() // Create Threads and attach to users
                )->each(static function($thread){
                    $thread->posts()->createMany(
                        factory(Post::class, 3)->state('create')->raw() // Add posts to thread
                    );
                });

            })
            ->each(static function ($user){
                $user->collections()->createMany(
                    factory(Collection::class, 4)->raw() // Create Collections
                )
                ->each(function ($collection) use ($user){
                    $collection->threads()->attach($collection->id, ['user_id'=>$user->id]); // Add threads to collections
                });
            });



    }
}
