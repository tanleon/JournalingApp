<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{
     use HasFactory;

     protected $fillable = ['name'];

     // One to many relationship
     public function entries()
     {
          return $this->hasMany(Entry::class);
     }
}