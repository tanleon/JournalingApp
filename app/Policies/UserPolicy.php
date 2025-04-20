<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
// Allow admins to bypass all checks
        if ($user->role === 'admin') {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        // Only admins can view all users
    return $user->role === 'admin';
    }

    public function view(User $user, User $targetUser)
    {
    // Users can view their own profile or admins can view any profile
        return $user->id === $targetUser->id || $user->role === 'admin';
    }

    public function create(User $user)
    {
        return $user->role === 'admin'; // Only admins can create users
    }

    public function update(User $user, User $targetUser)
    {
        return $user->id === $targetUser->id; // Users can update their own profile
    }

    public function delete(User $user, User $targetUser)
    {
        // Only admins can delete users
        return $user->role === 'admin';
    }
}
