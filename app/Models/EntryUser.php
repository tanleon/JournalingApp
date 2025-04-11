<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EntryUser extends Pivot
{
     // One to many relationship (inverse)
     public function role()
     {
          return $this->belongsTo(Role::class);
     }
}
