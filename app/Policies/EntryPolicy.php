<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
{
    use HandlesAuthorization;

    public function author(User $user, Entry $entry)
    {
        return $user->id === $entry->user_id;
    }
}
