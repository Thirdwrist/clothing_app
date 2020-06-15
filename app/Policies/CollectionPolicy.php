<?php

namespace App\Policies;

use App\Models\Collection;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function update(User $user, Collection $collection)
    {
        return $user->id === $collection->user_id;
    }

    public function destroy(User $user, Collection $collection)
    {
        return $user->id === $collection->user_id;
    }

    public function addThread(User $user, Collection $collection)
    {
        return $user->id === $collection->user_id;
    }

    public function removeThread(User $user, Collection $collection)
    {
        return $user->id === $collection->user_id;
    }
}
