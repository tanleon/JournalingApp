<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
{
// Prevent access if the user has no role
        if (is_null($user->role)) {
            return false;
        }

        // Allow admins to bypass all checks
    if ($user->role === 'admin') {
        return true;
    }
}

public function viewAny(User $user)
    {
// Allow only admins or authors to view all entries
        return $user->role === 'admin' || $user->role === 'author';
    }

    public function view(User $user, Entry $entry)
    {
// Allow admins or the owner of the entry to view it
        return $user->role === 'admin' || $user->id === $entry->user_id;
    }

    public function create(User $user)
    {
                return $user->role === 'author'; // Only authors can create entries
    }

    public function update(User $user, Entry $entry)
    {
        return $user->id === $entry->user_id || $user->role === 'admin'; // Owners or admins can update
    }

    public function delete(User $user, Entry $entry)
    {
return $user->id === $entry->user_id || $user->role === 'admin'; // Owners or admins can delete
}

    public function createCopy(User $user, Entry $entry)
    {
        // Only the owner of the entry can create a copy
        return $user->id === $entry->user_id;
    }
    }       
    