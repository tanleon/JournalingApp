<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ImageFactory extends Factory
{
     /**
      * The name of the factory's corresponding model.
      *
      * @var string
      */
     protected $model = Image::class;

     /**
      * Define the model's default state.
      *
      * @return array
      */
     public function definition()
     {
          // Ensure the directory exists
          $directory = storage_path('app/public/entries');
          if (!is_dir($directory)) {
               mkdir($directory, 0755, true);
          }

          // Generate a random image
          $imagePath = $this->faker->image($directory, 640, 480, null, false);

          return [
               'path' => 'entries/' . $imagePath
          ];
     }
}
