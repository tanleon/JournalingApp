<div class="menu" id="menu">
     <div class="overlay"></div>

     <div class="menu-container">

          <span class="material-icons-outlined bttn-close">close</span>

          <ul class="options">
               <div class="general">
                    {{-- Entries --}}
                    <li class="@if( request()->routeIs("entries.index") ) active @endif">
                         <a href="{{ route('entries.index') }}">
                              <span class="material-icons-outlined icons">lightbulb</span>
                              Entries
                         </a>
                    </li>

                    {{-- Trash --}}
                    <li class="@if( request()->routeIs("entries.trash") ) active @endif">
                         <a href="{{ route('entries.trash') }}">
                              <span class="material-icons-outlined icons">delete</span>
                              Trash
                         </a>
                    </li>
               </div>

               <div class="labels">
                    <h3 class="title">Labels</h3>
                    
                    @foreach ($user->labels as $label)
                        <li @if( request()->routeIs("labels.show") && isset($currentLabel) && $currentLabel == $label) class="active" @endif>
                              <a href="{{ route("labels.show", $label) }}">
                                   <span class="material-icons-outlined icons">label</span>
                                   {{ $label->name }}
                              </a> 
                         </li>
                    @endforeach

                    <li class="edit_label-bttn" id="edit_label-bttn">
                         <button>
                              <span class="material-icons-outlined icons">edit</span>
                              Edit labels
                         </button>
                    </li>

                    <li class="edit_label-bttn" id="create_label-bttn">
                         <button>
                              <span class="material-icons-outlined icons">add</span>
                              Create new label
                         </button>
                    </li>
               </div>
          </ul>

     </div>

</div>