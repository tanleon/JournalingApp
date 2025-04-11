<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
     public function profile()
     {
          $user = Auth::user();
          return view('user.profile', compact('user'));
     }

     public function update(Request $request)
     {
          $user = Auth::user();

          $request->validate([
               'name' => 'required|string|max:255',
               'email' => 'required|email|max:255|unique:users,email,' . $user->id,
               'image_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
               'current-password' => 'nullable|string',
               'new-password' => 'nullable|string|min:8|confirmed', // Ensure confirmation is validated
          ]);

          // Update user details
          $user->name = $request->name;
          $user->email = $request->email;

          // Handle profile image upload
          if ($request->hasFile('image_profile')) {
               $imagePath = $request->file('image_profile')->store('profile_images', 'public');
               $user->image()->updateOrCreate([], ['path' => '/storage/' . $imagePath]);
          }

          // Handle password update
          if ($request->filled('current-password') && $request->filled('new-password')) {
               if (Hash::check($request->input('current-password'), $user->password)) {
                    $user->password = Hash::make($request->input('new-password'));
               } else {
                    return redirect()->back()->withErrors(['current-password' => 'Current password is incorrect.']);
               }
          }

          $user->save();

          return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
     }
}
