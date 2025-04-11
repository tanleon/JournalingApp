<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Emotion;

class EmotionSeeder extends Seeder
{
     public function run()
     {
          $emotions = [
               'Neutral', 'Joy', 'Gratitude', 'Love', 'Excitement',
               'Hope', 'Pride', 'Contentment', 'Amusement', 'Inspiration',
               'Relief', 'Sadness', 'Anger', 'Fear', 'Disgust',
               'Guilt', 'Shame', 'Frustration', 'Loneliness', 'Anxiety',
               'Regret', 'Surprise', 'Nostalgia', 'Curiosity', 'Confusion',
               'Empathy', 'Anticipation', 'Acceptance', 'Boredom', 'Calmness',
               'Determination'
          ];

          foreach ($emotions as $emotion) {
               Emotion::updateOrCreate(['name' => $emotion]);
          }
     }
}