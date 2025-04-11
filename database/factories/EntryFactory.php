<?php

namespace Database\Factories;

use App\Models\Emotion;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
     /**
      * The name of the factory's corresponding model.
      *
      * @var string
      */
     protected $model = Entry::class;

     /**
      * Define the model's default state.
      *
      * @return array
      */
     public function definition()
     {
          $title = $this->faker->sentence(6);
          $body = $this->faker->paragraphs(rand(2, 5), true);

          return [
               'title' => $title,
               'body' => $body,
               'delete' => 0, // Default to not deleted
               'emotion_id' => Emotion::all()->random()->id,
               'user_id' => User::all()->random()->id,
          ];
     }
}