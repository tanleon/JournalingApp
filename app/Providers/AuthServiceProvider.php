<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Entry;
use App\Models\Label;
use App\Models\User;
use App\Policies\EntryPolicy;
use App\Policies\LabelPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
     /**
      * The policy mappings for the application.
      *
      * @var array
      */
     protected $policies = [
          Entry::class => EntryPolicy::class,
Label::class => LabelPolicy::class,
          User::class => UserPolicy::class,
     ];

     /**
      * Register any authentication / authorization services.
      *
      * @return void
      */
     public function boot()
     {
          $this->registerPolicies();

          // Define Gates for admin roles
          Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });
    
        // Define Gates for author roles
        Gate::define('isAuthor', function ($user) {
            return $user->role === 'author';
        });
     }
}