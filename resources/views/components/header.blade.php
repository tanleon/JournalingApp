<div class="header-container">

     {{-- Header bar --}}
     <header class="header">
          <button class="sidebar-button" id="sidebar-button">
               <span class="material-icons-outlined">&#xe5d2;</span>
          </button>

          @isset($trash)
               <form action="{{ route('entries.empty_trash') }}" method="post" class="empty-trash">
                    @csrf
                    @method('delete')
                    <button type="submit">Empty Trash Now</button>
               </form>
          @else
               <form action="{{ route('entries.search') }}" method="post" class="input-search">
                    @csrf
                    <input type="text" name="search" id="entry-name" placeholder=" &#xf002 Search your entries" style="font-family:Arial, FontAwesome" value="@isset($search){{ $search }}@endisset" >
                    <button type="submit">
                         <span class="material-icons-outlined">&#xf1df;</span>
                    </button>
               </form>
          @endisset

          <div class="right-component">
               <div class="profile-image">
                    <img src="{{ $user->image ? asset($user->image->path) : asset('/img/image-defaut.png') }}" alt="Profile Image" class="dropdown-user-img">
               </div>
          </div>
     </header>

     {{-- Dropdown user --}}
     <div class="dropdown">
          <div class="user-data">
               <a href="{{ route('user.profile')}}" class="user-image">
                    <img src="{{ $user->image ? asset($user->image->path) : asset('/img/image-defaut.png') }}" alt="Profile Image">
                    <button class="material-icons-outlined icon-camera">&#xe3c9;</button>
               </a>
               
               <h2 class="username">{{$user->name}}</h2>
               <span class="email">{{$user->email}}</span>
          </div>

          <div class="user-options">
               <a href="{{ route('user.profile') }}"><span class="material-icons-round">&#xe853;</span> Profile</a>
               <a href="{{ route('login.logout') }}"><span class="material-icons-outlined">&#xe9ba;</span> Log out</a>
          </div>

          <span class="material-icons-outlined close-bttn">&#xe5cd;</span>
     </div>
</div>