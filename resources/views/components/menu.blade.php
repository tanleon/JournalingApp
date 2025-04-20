<div class="menu" id="menu">
     <div class="overlay"></div>

     <div class="menu-container">

          <span class="material-icons-outlined bttn-close">close</span>

          <ul class="options">
               <div class="general">
                    {{-- Entries --}}
                    <li class="@if( request()->routeIs("entries.index") ) active @endif">
                         <a href="{{ route('entries.index') }}" class="menu-link">
                              <span class="material-icons-outlined icons">lightbulb</span>
                              Entries
                         </a>
                    </li>

                    {{-- Trash --}}
                    <li class="@if( request()->routeIs("entries.trash") ) active @endif">
                         <a href="{{ route('entries.trash') }}" class="menu-link">
                              <span class="material-icons-outlined icons">delete</span>
                              Trash
                         </a>
                    </li>

                    {{-- Dark/Light Mode using cookies--}}
                    <li>
                         <a href="javascript:void(0)">
                              <div style="display: flex; align-items: center;">
                                   <input type="checkbox" class="checkbox" id="checkbox"
                                    {{ (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark') ? 'checked' : '' }}>
                                   <label for="checkbox" class="checkbox-label" style="margin-right: 10px;">
                                        <i class="fas fa-moon"></i>
                                        <i class="fas fa-sun"></i>
                                        <span class="ball"></span>
                                   </label>
                                   <p class="theme-text" style="margin: 0;">Light/Dark Mode</p>
                              </div>
                         </a>
                    </li>
               </div>

               <div class="labels">
                    <h3 class="title">Labels</h3>
                    
                    @foreach ($user->labels as $label)
                        <li @if( request()->routeIs("labels.show") && isset($currentLabel) && $currentLabel == $label) class="active" @endif>
                              <a href="{{ route("labels.show", $label) }}" class="menu-link">
                                   <span class="material-icons-outlined icons">label</span>
                                   {{ $label->name }}
                              </a> 
                         </li>
                    @endforeach

                    <li class="edit_label-bttn" id="edit_label-bttn">
                         <button class="menu-button">
                              <span class="material-icons-outlined icons">edit</span>
                              Edit labels
                         </button>
                    </li>

                    <li class="edit_label-bttn" id="create_label-bttn">
                         <button class="menu-button">
                              <span class="material-icons-outlined icons">add</span>
                              Create new label
                         </button>
                    </li>
               </div>
          </ul>

     </div>

</div>

<style>
     @import url("https://fonts.googleapis.com/css2?family=Montserrat&display=swap");

     * {box-sizing: border-box;}

     body {
          font-family: "Montserrat", sans-serif;
          background-color: #fff;
          color: #000;
          transition: background 0.2s linear, color 0.2s linear;
     }

     body.dark {
          background-color: #292c35;
          color: #fff;
     }

     .menu-container {
          background-color: #f9f9f9;
          transition: background 0.2s linear, color 0.2s linear;
     }

     body.dark .menu-container {
          background-color: #333;
          color: #fff;
     }

     .menu-link {
          color: inherit;
          transition: color 0.2s linear;
     }

     body.dark .menu-link {
          color: #fff;
     }

     .checkbox {
          opacity: 0;
          position: absolute;
     }

     .checkbox-label {
          background-color: #111;
          width: 50px;
          height: 26px;
          border-radius: 50px;
          position: relative;
          padding: 5px;
          cursor: pointer;
          display: flex;
          justify-content: space-between;
          align-items: center;
     }

     .fa-moon {color: #f1c40f;}

     .fa-sun {color: #f39c12;}

     .checkbox-label .ball {
          background-color: #fff;
          width: 22px;
          height: 22px;
          position: absolute;
          left: 2px;
          top: 2px;
          border-radius: 50%;
          transition: transform 0.2s linear;
     }

     .checkbox:checked + .checkbox-label .ball {
          transform: translateX(24px);
     }

     .menu-button {
          background: none;
          border: none;
          color: inherit;
          cursor: pointer;
          transition: color 0.2s linear;
     }

     body.dark .menu-button {
          color: #fff;
     }

     .icons {
          color: inherit;
          transition: color 0.2s linear;
     }

     body.dark .icons {
          color: #fff;
     }

     .title {
          color: inherit;
          transition: color 0.2s linear;
     }

     body.dark .title {
          color: #fff;
     }

     .theme-text {
          color: inherit;
          transition: color 0.2s linear;
     }

     body.dark .theme-text {
          color: #fff;
     }
     .menu-container li.active {
          background-color: lightgrey;
          transition: background-color 0.2s linear;
     }

     body.dark .menu-container li.active {
          background-color: darkgrey;
     }

     header.header .input-search input {
          transition: color 0.2s linear, background-color 0.2s linear;
     }

     body.dark header.header .input-search input {
          color: grey !important;
     }
     
     .input-search button {
          color: lightgrey;
          background: none;
          border: none;
          cursor: pointer;
          transition: color 0.2s linear;
     }
     body.dark .input-search button {
          color: grey;
     }

     header.header {
          background-color: #fff;
          transition: background-color 0.2s linear;
     }

     body.dark header.header {
          background-color: grey;
     }

     .input-search input {
          color: grey !important; /* Ensure the text color is light gray */
          transition: color 0.2s linear, background-color 0.2s linear;
     }

     .input-search input::placeholder {
          color: grey;
          transition: color 0.2s linear;
     }

     body.dark .input-search input::placeholder {
          color: white !important; 
     }
</style>

<script>
     // Apply the theme on page load based on the cookie
     document.addEventListener('DOMContentLoaded', () => {
          const currentTheme = document.cookie.replace(/(?:(?:^|.*;\s*)theme\s*\=\s*([^;]*).*$)|^.*$/, "$1");
          if (currentTheme === 'dark') {
               document.body.classList.add('dark');
               document.querySelector('.menu-container').classList.add('dark');
          }
     });

     // Toggle theme and update the cookie
     const checkbox = document.getElementById("checkbox");
     checkbox.addEventListener("change", () => {
          const newTheme = checkbox.checked ? 'dark' : 'light';
          document.cookie = `theme=${newTheme}; path=/;`;
          document.body.classList.toggle('dark', checkbox.checked);
          document.querySelector('.menu-container').classList.toggle('dark', checkbox.checked);
     });
</script>