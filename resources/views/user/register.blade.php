@extends('layouts.template')

@section('title', 'Create your Account - Entry App')

@section('content')

<div class="card-form-container">
     <div class="form">
          <h2>Create your account:</h2>

          <form action="{{ route('login.register') }}" method="POST">
               @csrf

               <div class="input-container">
                    <label for="name">Full Name:</label>
                    <span class="material-icons icon">&#xe853;</span>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
               </div>

               <div class="input-container">
                    <label for="email">Email:</label>
                    <span class="material-icons icon">&#xe158;</span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
               </div>

               <div class="input-container">
                    <label for="password">Password:</label>
                    <span class="material-icons icon">&#xe897;</span>
                    <input type="password" name="password" id="password" value="" required>

                    <div class="show-password">
                         <input type="checkbox" id="show-password">
                         <label for="show-password">Show password</label>
                    </div>
               </div>

               <div class="input-container">
                    <label for="confirm-password">Confirm Password:</label>
                    <span class="material-icons icon">&#xe897;</span>
                    <input type="password" name="confirm_password" id="confirm-password" value="" required>

                    <div class="show-password">
                         <input type="checkbox" id="show-confirm-password">
                         <label for="show-confirm-password">Show password</label>
                    </div>
               </div>

               <div class="input-container sumbit">
                    <button type="submit" value="Sign up" style="padding: 10px; width: 50%; background-color: #3e3e3e; color: white; border: none; border-radius: 5px; transition: background-color 0.3s;" 
                         onmouseover="this.style.backgroundColor='#ff9900';" 
                         onmouseout="this.style.backgroundColor='#3e3e3e';">
                         <strong>Sign Up</strong>
                    </button>
                    <a href="{{ route('login.index') }}" class="create-account">Sign in instead</a>
               </div>
          </form>
     </div>
</div>

@endsection

@section('scripts')
     @include('entries.components.alerts-js') <!-- Updated from notes to entries -->
     <script>
          document.addEventListener("change", e => {
               if (e.target.matches(".show-password *")) {
                    let $input = e.target.closest(".input-container").querySelector("input");

                    if ($input.type == "password")
                         $input.type = "text";
                    else
                         $input.type = "password";
               }
          });
     </script>
@endsection