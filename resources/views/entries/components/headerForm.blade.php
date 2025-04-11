@isset($readOnly)
     <div class="options">
          <button class=" button-icon" type="submit"><span class="material-icons-outlined">&#xe938;</span> Restore</button>
     </div>
@else
    <div class="options">
          <button class=" button-icon" type="submit"><span class="material-icons-outlined">&#xe161;</span> Save</button>
          <button class="material-icons-outlined icons dropdown-menu-options" type="button">&#xe5d4;</button>
     </div>

     <div class="dropdown-emotions dropdown-editor">
          <select name="emotion" id="emotion-dropdown" class="emotion-dropdown">
               @foreach (\App\Models\Emotion::all() as $emotion)
                    <option value="{{$emotion->id}}" {{ isset($entry) && $entry->emotion_id == $emotion->id ? 'selected' : '' }}>
                         {{$emotion->name}}
                    </option>
               @endforeach
          </select>
     </div>

     <textarea name="body" id="entry-body" class="entry-textarea"></textarea>
     <input type="text" name="entry-title" id="entry-title" class="entry-title" value="">
@endisset