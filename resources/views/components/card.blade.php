@php
     // Define emotion-to-color mapping
     $emotionColors = [
          'Neutral' => '#d3d3d3',
          'Joy' => '#ffeb3b',
          'Gratitude' => '#ff9800',
          'Love' => '#e91e63',
          'Excitement' => '#ff5722',
          'Hope' => '#4caf50',
          'Pride' => '#9c27b0',
          'Contentment' => '#8bc34a',
          'Amusement' => '#ffc107',
          'Inspiration' => '#03a9f4',
          'Relief' => '#00bcd4',
          'Sadness' => '#2196f3',
          'Anger' => '#f44336',
          'Fear' => '#673ab7',
          'Disgust' => '#70dd22',
          'Guilt' => '#607d8b',
          'Shame' => '#9e9e9e',
          'Frustration' => '#ff7043',
          'Loneliness' => '#3f51b5',
          'Anxiety' => '#009688',
          'Regret' => '#ff5252',
          'Surprise' => '#ffcc80',
          'Nostalgia' => '#ffab91',
          'Curiosity' => '#ffb74d',
          'Confusion' => '#cddc39',
          'Acceptance' => '#a257ff'
     ];

     // Get the color for the current emotion or fallback to white
     $cardColor = $emotionColors[$entry->emotion->name] ?? '#ffffff';
@endphp

<div class="card-container grid-item" 
     @isset($trash) onclick="toggleDropdown(this);" 
     @else onclick="window.location='{{ route('entries.show', $entry) }}';" 
     @endisset 
     style="cursor: pointer; align-items: center; display: flex; justify-content: center; padding-top: 10px;">
     <div class="card hover-effect" 
          style="width: 70%; background-color: {{ $cardColor }}; border: 1px solid {{ $cardColor }}; border-radius: 8px; position: relative; transition: transform 0.3s, box-shadow 0.3s;">
          <style>
               .card-container:hover .card {
                    transform: scale(1.05);
                    box-shadow: 0 8px 16px #3e3e3e;
               }
          </style>

          {{-- Card created_at --}}
          <div class="card-header" style="margin-bottom: 10px;">
               <span class="created-at" style="
                    font-size: 1.4rem; 
                    font-weight: bold; 
                    color: {{ (hexdec(substr($cardColor, 1, 2)) * 0.299 + hexdec(substr($cardColor, 3, 2)) * 0.587 + hexdec(substr($cardColor, 5, 2)) * 0.114) > 186 ? '#000000' : '#ffffff' }}; 
                    background-color: transparent; 
                    padding: 2px 4px;">
                    {{ $entry->created_at->format('Y-m-d H:i:s') }}
               </span>
          </div>

          {{-- Card title & body preview --}}
          <h3 style="
               font-size: 1.8rem; 
               font-weight: bold; 
               margin-bottom: 8px; 
               color: {{ (hexdec(substr($cardColor, 1, 2)) * 0.299 + hexdec(substr($cardColor, 3, 2)) * 0.587 + hexdec(substr($cardColor, 5, 2)) * 0.114) > 186 ? '#000000' : '#ffffff' }};">
               {{ $entry->title }}
          </h3>

          <div class="content" style="
               font-size: 1.2rem; 
               color: {{ (hexdec(substr($cardColor, 1, 2)) * 0.299 + hexdec(substr($cardColor, 3, 2)) * 0.587 + hexdec(substr($cardColor, 5, 2)) * 0.114) > 186 ? '#000000' : '#ffffff' }}; 
               line-height: 1.5;">
               {{ Str::limit(strip_tags($entry->body), 100, '...') }}
          </div>

          {{-- Card tags --}}
          <div class="tags-container" style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 5px;">
               @foreach ($entry->labels as $label)
                    <span class="label" style="
                         font-size: 1rem; 
                         color: #fff; 
                         background-color: #007bff; 
                         padding: 4px 8px; 
                         border-radius: 4px;">
                         {{ $label->name }}
                    </span>
               @endforeach
          </div>

          {{-- Emotion at the middle bottom --}}
          <div class="emotion-container" style="margin-top: 15px; text-align: center;">
               <span class="emotion-name" style="
                    font-size: 1.2rem; 
                    font-weight: bold; 
                    color: {{ (hexdec(substr($cardColor, 1, 2)) * 0.299 + hexdec(substr($cardColor, 3, 2)) * 0.587 + hexdec(substr($cardColor, 5, 2)) * 0.114) > 186 ? '#000000' : '#ffffff' }};">
                    {{ $entry->emotion->name }}
               </span>
          </div>

          {{-- Options Dropdown --}}
          @isset($trash)
               <div class="dropdown" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); color: {{ (hexdec(substr($cardColor, 1, 2)) * 0.299 + hexdec(substr($cardColor, 3, 2)) * 0.587 + hexdec(substr($cardColor, 5, 2)) * 0.114) > 186 ? '#000000' : '#ffffff' }}; border: 1px solid #ccc; border-radius: 4px; display: none; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 200px;">
                    {{-- Delete button --}}
                    <form action="{{ route('entries.destroy', $entry) }}" method="post" style="margin-bottom: 10px;">
                         @csrf
                         @method("delete")
                         <button type="submit" class="menu-item" style="display: block; width: 100%; text-align: left; padding: 5px 10px; background: none; border: none; font-size: 1rem; color: #333; cursor: pointer;">Delete Entry</button>
                    </form>

                    {{-- Restore button --}}
                    <form action="{{ route('entries.restore', $entry) }}" method="post" style="margin-bottom: 10px;">
                         @csrf
                         @method('put')
                         <button type="submit" class="menu-item" style="display: block; width: 100%; text-align: left; padding: 5px 10px; background: none; border: none; font-size: 1rem; color: #333; cursor: pointer;">Restore Entry</button>
                    </form>
               </div>
          @else
               <div class="options" style="position: absolute; top: 10px; right: 10px;" onclick="event.stopPropagation(); toggleDropdown(this);">
                    <button class="material-icons-outlined dropdown-menu-bttn" style="border: none; background: none; font-size: 1.5rem; cursor: pointer; color: #666;">&#xe5d4;</button>
                    <div class="dropdown" style="position: absolute; top: 100%; right: 0; background: #fff; border: 1px solid #ccc; border-radius: 4px; display: none; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 200px;">
                         {{-- Delete button --}}
                         <form action="{{ route('entries.sendTrash', $entry) }}" method="post" style="margin-bottom: 10px;">
                              @csrf
                              @method("delete")
                              <button type="submit" class="menu-item" style="display: block; width: 100%; text-align: left; padding: 5px 10px; background: none; border: none; font-size: 1rem; color: #333; cursor: pointer;">Delete Entry</button>
                         </form>
                         <a href="{{ route('entries.make_copy', $entry) }}" class="menu-item" style="display: block; width: 100%; text-align: left; padding: 5px 10px; font-size: 1rem; text-decoration: none;">Make a copy</a>
                    </div>
               </div>
          @endisset
     </div>
</div>

<script>
     function toggleDropdown(element) {
          const dropdown = element.querySelector('.dropdown');
          dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
     }
</script>
