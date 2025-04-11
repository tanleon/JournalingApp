<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
     use HasFactory;

     protected $guarded = [
          'id',
          'created_at',
          'updated_at'
     ];

     // One to many relationship (inverse)
     public function emotion()
     {
          return $this->belongsTo(Emotion::class);
     }

     // Many to many relationship
     public function user()
     {
          return $this->belongsTo(User::class);
     }

     public function labels()
     {
          return $this->belongsToMany(Label::class, 'entry_label', 'entry_id', 'label_id');
     }

     // Polymorphic one to many relationship
     public function images()
     {
          return $this->morphMany(Image::class, 'imageable');
     }
}