<?php

namespace Database\Factories;

use App\Models\Label;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabelFactory extends Factory
{
     /**
      * The name of the factory's corresponding model.
      *
      * @var string
      */
     protected $model = Label::class;

     /**
      * Define the model's default state.
      *
      * @return array
      */
     public function definition()
     {
          // Generate a random label name
          $topics = ['Work', 'Personal', 'Health', 'Travel', 'Ideas', 'Gratitude', 'Goals', 'Dreams', 'Reflection', 'Family'];

          // Use Faker to generate random words if the predefined list is exhausted
          $randomTopic = $this->faker->unique()->word();

          return [
               'name' => $this->faker->randomElement(array_merge($topics, [$randomTopic])),
               'user_id' => User::inRandomOrder()->first()->id,
          ];
     }
}
