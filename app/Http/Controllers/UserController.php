<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class UserController extends Controller
{
     public function profile()
     {
          $user = Auth::user();
          return view('user.profile', compact('user'));
     }

     public function index()
     {
          // Ensure only admins can view all users
          if (!Gate::allows('isAdmin')) {
               abort(403, 'Unauthorized action.');
          }

               $this->authorize('viewAny', User::class); // Maps to UserPolicy::viewAny

               $users = User::all(); // Fetch all users
               return view('user.index', compact('users')); // Pass users to the view
     }

     public function show(User $user)
     {
          // Use the 'view' policy method to check if the user can view the profile
          $this->authorize('view', $user);

          return view('user.show', compact('user'));
     }

     public function destroy(User $user)
     {
          // Ensure only admins can delete users
          if (!Gate::allows('isAdmin')) {
               abort(403, 'Unauthorized action.');
          }

          $this->authorize('delete', $user); // Maps to UserPolicy::delete

          $user->delete(); // Delete the user
          return redirect()->route('users.index')->with('success', 'User deleted successfully.');
     }
}
