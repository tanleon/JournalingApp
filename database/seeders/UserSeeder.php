<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
     /**
      * Run the database seeds.
      *
      * @return void
      */
     public function run()
     {
          // Create a test user
          if (!User::where('email', 'example@gmail.com')->exists()) {
               $testUser = User::create([
                    'name' => 'Test User',
                    'email' => 'example@gmail.com',
                    'password' => bcrypt('password'),
               ]);

               Image::factory()->create([
                    'user_id' => $testUser->id,
               ]);
          }

          // Create additional users with profile images
          User::factory(5)->create()->each(function ($user) {
               Image::factory()->create([
                    'user_id' => $user->id,
               ]);
          });
     }
}
