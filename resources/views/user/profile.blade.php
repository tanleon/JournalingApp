@extends('layouts.template')

@section('title', 'Profile - Entry App')

@section('header')
     <header class="header-entry">
          <div class="container" style="display: flex; gap: 1rem; align-items: center;">
               <a href="{{route("entries.index")}}" class="back-arrow" style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="material-icons-outlined">&#xe5c4;</span> Back
               </a>
          </div>
     </header>
     <div class="pageTitle">
          <h1 class="profile-title" style="font-size: 3vh;">User Profile: </h1>
     </div>
@endsection

@section('content')
     <form action="{{ route("user.update") }}" class="profile-form" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="input-container-image">
               <label class="profile-image" for="file">
                    <figure>
                         <img src="{{ $user->image ? asset($user->image->path) : asset('/img/image-defaut.png') }}" alt="Profile Image" id="picture">
                    </figure>
                    <span class="material-icons-outlined icons edit">&#xe3c9;</span>
               </label>
               <input type="file" name="image_profile" id="file" accept="image/*">
          </div>

          <div class="input-container">
               <label for="name"><strong>Name: </strong></label>
               <span class="material-icons-round icon">&#xe853;</span> 
               <input type="text" name="name" id="name" value="{{ $user->name }}">
          </div>

          <div class="input-container">
               <label for="email"><strong>Email: </strong></label>
               <span class="material-icons icon">&#xe158;</span>
               <input type="text" name="email" id="email" value="{{ $user->email }}">
          </div>

          <div class="input-container">
               <label for="current-password"><strong>[Optional] Current Password: </strong></label>
               <span class="material-icons icon">&#xe897;</span>
               <input type="password" name="current-password" id="current-password" value="">
          </div>

          <div class="input-container">
               <label for="new-password"><strong>[Optional] New Password: </strong></label>
               <span class="material-icons icon">&#xe897;</span>
               <input type="password" name="new-password" id="new-password" value="">
          </div>

          <div class="input-container">
               <label for="new-password-confirmation"><strong>[Optional] Confirm New Password: </strong></label>
               <span class="material-icons icon">&#xe897;</span>
               <input type="password" name="new-password_confirmation" id="new-password-confirmation" value="">
          </div>

          <div class="input-container">
               <button type="submit" value="Save" style="padding: 10px; width: 50%; background-color: #3e3e3e; color: white; border: none; border-radius: 5px; transition: background-color 0.3s;" 
                    onmouseover="this.style.backgroundColor='#ff9900';" 
                    onmouseout="this.style.backgroundColor='#3e3e3e';">
                    <strong>Sign Up</strong>
               </button>
          </div>
     </form>
@endsection

@section('scripts')
     @include('entries.components.alerts-js') <!-- Updated from notes to entries -->
     
     {{-- Previsualization Image --}}
     <script>
          document.getElementById("file").addEventListener('change', e => {
               let file = e.target.files[0];
               let reader = new FileReader();

               reader.onload = e => document.getElementById("picture").setAttribute('src', e.target.result);
               reader.readAsDataURL(file);
          });
     </script>
@endsection