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
                    <input type="text" name="search" id="entry-name" placeholder=" &#xf002 Search your entries" style="font-family:Arial, FontAwesome" value="@isset($search){{ $search }}@endisset" required>
                    <button type="submit">
                         <span class="material-icons-outlined">&#xf1df;</span>
                    </button>
               </form>
          @endisset

          <div class="right-component">
               <div class="profile-image">
                    <img src="@if($user->image) {{$user->image->path}} @else {{asset("/img/image-defaut.png")}} @endif" alt="Undisplayable image" srcset="" class="dropdown-user-img">
               </div>
          </div>
     </header>

     {{-- Dropdown user --}}
     <div class="dropdown">
          <div class="user-data">
               <a href="{{ route('user.profile')}}" class="user-image">
                    <img src="@if($user->image) {{$user->image->path}} @else {{asset("/img/image-defaut.png")}} @endif" alt="Undisplayable image" srcset="">
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

<header class="header-note">
     <div class="container">

          @isset($readOnly)
               <a href="{{route("entries.trash")}}" class="material-icons-outlined icons">&#xe5c4;</a>
          @else 
               <a href="{{route("entries.index")}}" class="material-icons-outlined icons">&#xe5c4;</a>
          @endisset

          @if($method == 'put')
               @isset($readOnly)
                    <form action="{{route("entries.restore", $entry)}}" method="post" id="entry-form">
                         @csrf
                         @method("put")
                         @include('entries.components.headerForm')
                         <select name="emotion" class="emotion-dropdown">
                              <option value="happy">Happy</option>
                              <option value="sad">Sad</option>
                              <option value="neutral">Neutral</option>
                         </select>
                    </form>
               @else
                    <form action="{{route("entries.update", $entry)}}" method="post" id="entry-form">
                         @csrf
                         @method("put")
                         @include('entries.components.headerForm')
                         <select name="emotion" class="emotion-dropdown">
                              <option value="happy">Happy</option>
                              <option value="sad">Sad</option>
                              <option value="neutral">Neutral</option>
                         </select>
                    </form>
               @endisset
          @else
               <form action="{{ route('entries.store') }}" method="post" id="entry-form">
                    @csrf
                    @method("post")
                    @include('entries.components.headerForm')
               </form>
          @endif

          @isset($edit)
              <div class="dropdown-options dropdown-editor">
                    <a href="" class="menu-item">Add label</a> 
                    
                    @if($method == 'put')
                         <form action="{{ route('entries.sendTrash', $entry) }}" method="post">
                              @csrf
                              @method("delete")
                              <button type="submit" class="menu-item">Delete Entry</button>
                         </form>
                         <a href="{{ route('entries.make_copy', $entry) }}" class="menu-item">Make a copy</a>
                    @endif
               </div>
          @endisset
     </div>
</header>