@extends('layouts.template')

@section('title', 'Create Note - Note App')

@section('styles')
     <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
     <style>
          .entry-container {
               background-color: #f9f9f9;
               padding: 20px;
               border-radius: 8px;
               transition: background-color 0.2s linear, color 0.2s linear;
          }

          .title-input, .datetime-input, .emotion-dropdown, .label-input {
               background-color: #fff;
               color: #000;
               transition: background-color 0.2s linear, color 0.2s linear;
          }

          .editor-container {
               background-color: #fff;
               color: #000;
               transition: background-color 0.2s linear, color 0.2s linear;
          }
     </style>
@endsection

@section('content')
     <a href="{{ isset($readOnly) ? route('entries.trash') : route('entries.index') }}" class="back-arrow">
          <span class="material-icons-outlined">&#xe5c4;</span> Back
     </a>

     <form action="{{ route('entries.store') }}" method="post" id="entry-form">
          @csrf
          @isset($readOnly)
               <div class="options">
                    <button class=" button-icon" type="submit"><span class="material-icons-outlined">&#xe938;</span> Restore</button>
               </div>
          @else
                    <div class="options-row" style="display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap; margin: auto;">
                           
                           <div class="datetime-container" style="flex: 1; min-width: 200px;">
                                    <label for="entry-datetime" class="form-label">Date & Time:</label>
                                    <input type="datetime-local" name="created_at" id="entry-datetime" class="datetime-input" value="{{ now()->format('Y-m-d\TH:i') }}">
                           </div>

                           <div class="dropdown-emotions dropdown-editor" style="flex: 1; min-width: 200px;">
                                   <label for="emotion-dropdown" class="form-label">Emotion:</label>
                                    <select name="emotion_id" id="emotion-dropdown" class="emotion-dropdown">
                                              @foreach (\App\Models\Emotion::all() as $emotion)
                                                       <option value="{{$emotion->id}}" {{ old('emotion_id', \App\Models\Emotion::where('name', 'Neutral')->first()->id) == $emotion->id || (isset($entry) && $entry->emotion_id == $emotion->id) ? 'selected' : '' }}>
                                                                  {{$emotion->name}}
                                                       </option>
                                              @endforeach
                                    </select>
                                    @error('emotion_id')
                                              <div class="error-message">{{ $message }}</div>
                                    @enderror
                           </div>
                           
                            <div class="options" style="flex: 1; min-width: 150px; display: flex; justify-content: flex-end;">
                                      <button class="button-icon" type="submit" style="display: flex; align-items: center; justify-content: center; text-align: center;"><span class="material-icons-outlined">&#xe161;</span> Save</button>
                            </div>
                    </div>

               <input type="text" name="title" value="{{ old('title') }}" placeholder="Title" class="title-input" id="title-input">
               @error('title')
                    <div class="error-message">{{ $message }}</div>
               @enderror

               <div class="editor-container">
                    <div id="editor"></div>
               </div>
               <textarea name="body" id="entry-body" class="entry-textarea" hidden>{{ old('body') }}</textarea>
               @error('body')
                    <div class="error-message">{{ $message }}</div>
               @enderror

               <div class="label-container">
                    <h3>Labels:</h3>
                    <div id="current-labels">
                            <div class="dropdown-multiselect">
                                      <button type="button" class="dropdown-toggle" id="labelDropdownToggle">Select Labels</button>
                                      <div class="dropdown-menu" id="labelDropdownMenu">
                                               @foreach (\App\Models\Label::all() as $label)
                                                          <div class="dropdown-item">
                                                                   <input type="checkbox" id="label-{{ $label->id }}" name="labels[]" value="{{ $label->id }}"
                                                                             {{ isset($entry) && $entry->labels->contains($label->id) ? 'checked' : '' }}>
                                                                   <label for="label-{{ $label->id }}">{{ $label->name }}</label>
                                                          </div>
                                               @endforeach
                                               <div class="dropdown-item">
                                                        <input type="text" id="new-label-dropdown-input" placeholder="Add new label" class="label-input">
                                                        <button type="button" class="material-icons-outlined add-bttn" id="add-label-dropdown-btn">&#xe145;</button>
                                               </div>
                                      </div>
                            </div>

                            <style>
                                      .dropdown-multiselect {
                                               position: relative;
                                               display: inline-block;
                                      }

                                      .dropdown-toggle {
                                               background-color: #f8f9fa;
                                               border: 1px solid #ced4da;
                                               padding: 8px 12px;
                                               cursor: pointer;
                                               border-radius: 4px;
                                      }

                                      .dropdown-menu {
                                               display: none;
                                               position: absolute;
                                               background-color: #fff;
                                               border: 1px solid #ced4da;
                                               box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                               z-index: 1000;
                                               padding: 10px;
                                               max-height: 200px;
                                               overflow-y: auto;
                                               border-radius: 4px;
                                               bottom: 100%; /* Position the dropdown menu above the toggle button */
                                               transform: translateY(-10px); /* Add some spacing above the toggle button */
                                      }

                                      .dropdown-item {
                                               display: flex;
                                               align-items: center;
                                               gap: 8px;
                                               padding: 5px 0;
                                      }

                                      .dropdown-multiselect.open .dropdown-menu {
                                               display: block;
                                      }
                            </style>

                            <script>
                                      document.addEventListener('DOMContentLoaded', function () {
                                               const dropdownToggle = document.getElementById('labelDropdownToggle');
                                               const dropdownMenu = document.getElementById('labelDropdownMenu');
                                               const dropdownMultiselect = dropdownToggle.closest('.dropdown-multiselect');
                                               const addLabelDropdownBtn = document.getElementById('add-label-dropdown-btn');
                                               const newLabelDropdownInput = document.getElementById('new-label-dropdown-input');

                                               dropdownToggle.addEventListener('click', function () {
                                                          dropdownMultiselect.classList.toggle('open');
                                               });

                                               document.addEventListener('click', function (event) {
                                                          if (!dropdownMultiselect.contains(event.target)) {
                                                                   dropdownMultiselect.classList.remove('open');
                                                          }
                                               });

                                               addLabelDropdownBtn.addEventListener('click', function () {
                                                          const labelName = newLabelDropdownInput.value.trim();
                                                          if (labelName) {
                                                                 fetch('{{ route('labels.store') }}', {
                                                                       method: 'POST',
                                                                       headers: {
                                                                             'Content-Type': 'application/json',
                                                                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                       },
                                                                       body: JSON.stringify({ name: labelName })
                                                                 })
                                                                 .then(response => response.json())
                                                                 .then(data => {
                                                                       if (data.success) {
                                                                             const newLabel = document.createElement('div');
                                                                             newLabel.classList.add('dropdown-item');
                                                                             newLabel.innerHTML = `
                                                                                   <input type="checkbox" id="label-${data.label.id}" name="labels[]" value="${data.label.id}">
                                                                                   <label for="label-${data.label.id}">${data.label.name}</label>
                                                                             `;
                                                                             dropdownMenu.insertBefore(newLabel, newLabelDropdownInput.closest('.dropdown-item'));
                                                                             newLabelDropdownInput.value = '';
                                                                       } else {
                                                                             alert('Failed to add label.');
                                                                       }
                                                                 })
                                                                 .catch(error => {
                                                                       console.error('Error:', error);
                                                                       alert('An error occurred while adding the label.');
                                                                 });
                                                          } else {
                                                                 alert('Please enter a label name.');
                                                          }
                                               });
                                      });
                            </script>
                    </div>
               </div>
          @endisset
     </form>

@endsection

@section('scripts')
     <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
     <script>
          const quill = new Quill('#editor', {
               theme: 'snow'
          });

          const form = document.getElementById('entry-form');
          const bodyInput = document.getElementById('entry-body');

          form.addEventListener('submit', function () {
               bodyInput.value = quill.root.innerHTML;
          });

          // Set datetime-local field to current time in user's timezone
          const datetimeInput = document.getElementById('entry-datetime');
          const now = new Date();
          const offset = now.getTimezoneOffset() * 60000; // Offset in milliseconds
          const localISOTime = new Date(now - offset).toISOString().slice(0, 16);
          datetimeInput.value = localISOTime;

          // Add new label functionality
          const addLabelBtn = document.getElementById('add-label-btn');
          const newLabelInput = document.getElementById('new-label-input');
          const currentLabels = document.getElementById('current-labels');

          addLabelBtn.addEventListener('click', function () {
               const labelName = newLabelInput.value.trim();
               if (labelName) {
                    fetch('{{ route('labels.store') }}', {
                         method: 'POST',
                         headers: {
                              'Content-Type': 'application/json',
                              'X-CSRF-TOKEN': '{{ csrf_token() }}'
                         },
                         body: JSON.stringify({ name: labelName })
                    })
                    .then(response => response.json())
                    .then(data => {
                         if (data.success) {
                              const newLabel = document.createElement('div');
                              newLabel.classList.add('label-item');
                              newLabel.innerHTML = `
                                   <input type="checkbox" id="label-${data.label.id}" name="labels[]" value="${data.label.id}">
                                   <label for="label-${data.label.id}">${data.label.name}</label>
                              `;
                              currentLabels.appendChild(newLabel);
                              newLabelInput.value = '';
                         } else {
                              alert('Failed to add label.');
                         }
                    })
                    .catch(error => {
                         console.error('Error:', error);
                         alert('An error occurred while adding the label.');
                    });
               } else {
                    alert('Please enter a label name.');
               }
          });
     </script>
     @include('entries.components.alerts-js')
@endsection