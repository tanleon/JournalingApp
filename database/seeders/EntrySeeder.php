<?php

namespace Database\Seeders;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
     /**
      * Run the database seeds.
      *
      * @return void
      */
     public function run()
     {
          User::all()->each(function ($user) {
               Entry::factory(rand(10, 20))->create([
                    'user_id' => $user->id,
               ])->each(function ($entry) use ($user) {
                    // Attach random labels to each entry
                    $entry->labels()->attach($user->labels->random(rand(1, 3))->pluck('id'));
               });
          });
     }
}
