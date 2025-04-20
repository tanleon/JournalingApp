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
     <form action="{{ route('entries.store') }}" method="post" id="entry-form">
          @csrf
          @isset($readOnly)
               <a href="{{ isset($readOnly) ? route('entries.trash') : route('entries.index') }}" class="back-arrow" style="display: flex; align-items: center; justify-content: center; text-align: center; gap: 1rem;">
                    <span class="material-icons-outlined">&#xe5c4;</span> Back
               </a>
               <div class="options">
                    <button class="button-icon" type="submit">
                         <span class="material-icons-outlined">&#xe938;</span> Restore
                    </button>
               </div>
          @else
               <div class='top-nav-section' style="display: flex; gap: 1rem; align-items: center;">
                    <!-- Back Link -->
                    <a href="{{ isset($readOnly) ? route('entries.trash') : route('entries.index') }}" class="back-arrow" style="display: flex; align-items: center; gap: 0.5rem;">
                         <span class="material-icons-outlined">&#xe5c4;</span> Back
                    </a>

                    <!-- DateTime Picker -->
                    <div class="datetime-container">
                         <label for="entry-datetime" class="form-label"><strong>Date & Time: </strong></label>
                         <input type="datetime-local" name="created_at" id="entry-datetime" class="datetime-input" value="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <!-- Emotion Dropdown -->
                    <div class="dropdown-emotions dropdown-editor">
                         <label for="dropdown-dropdown" class="form-label"><strong>Emotion: </strong></label>
                         <select name="emotion_id" id="emotion-dropdown" class="emotion-dropdown">
                              @foreach (\App\Models\Emotion::all() as $emotion)
                                   <option value="{{ $emotion->id }}"
                                        {{ old('emotion_id', \App\Models\Emotion::where('name', 'Neutral')->first()->id) == $emotion->id || (isset($entry) && $entry->emotion_id == $emotion->id) ? 'selected' : '' }}>
                                        {{ $emotion->name }}
                                   </option>
                              @endforeach
                         </select>
                         @error('emotion_id')
                              <div class="error-message">{{ $message }}</div>
                         @enderror
                    </div>

                    <!-- Save Button aligned right -->
                    <div class="options" style="margin-left: auto;">
                         <button class="button-icon" type="submit" style="display: flex; align-items: center; gap: 0.5rem;">
                              <span class="material-icons-outlined">&#xe161;</span> Save
                         </button>
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
                    <div class="dropdown-multi-select">
                         <button type="button" class="dropdown-toggle" id="label-dropdown-toggle">Select Labels</button>
                         <div class="dropdown-menu" id="label-dropdown-menu">
                              @foreach (\App\Models\Label::all() as $label)
                                   <div class="dropdown-item">
                                        <input type="checkbox" id="label-{{ $label->id }}" name="labels[]" value="{{ $label->id }}"
                                             {{ isset($entry) && $entry->labels->contains($label->id) ? 'checked' : '' }}>
                                        <label for="label-{{ $label->id }}">{{ $label->name }}</label>
                                   </div>
                              @endforeach
                              <div id="new-label-section">
                                   <div class="dropdown-item add-label-section">
                                        <input type="text" id="new-label-input" name="new_label" placeholder="Custom label" class="label-input">
                                        <button type="button" class="material-icons-outlined add-bttn" id="add-label-btn">&#xe145;</button>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <!-- Custom style for dropdown -->
               <style>
                    .dropdown-multi-select {
                         position: relative;
                         display: inline-block;
                    }

                    .dropdown-toggle {
                         background-color: #f1f1f1;
                         border: 1px solid #ccc;
                         padding: 10px;
                         cursor: pointer;
                         width: 100%;
                         text-align: left;
                    }

                    .dropdown-menu {
                         display: none;
                         position: absolute;
                         background-color: #fff;
                         border: 1px solid #ccc;
                         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                         z-index: 1000;
                         max-height: 200px;
                         overflow-y: auto;
                         width: auto;
                         min-width: 100%;
                         top: auto;
                         bottom: 100%;
                    }

                    .dropdown-item {
                         padding: 10px;
                         display: flex;
                         align-items: center;
                    }

                    .dropdown-item input[type="checkbox"] {
                         margin-right: 10px;
                    }

                    .add-label-section {
                         border-top: 1px solid #ccc;
                         padding-top: 10px;
                         margin-top: 10px;
                    }
               </style>
               <!-- End custom style for dropdown -->
          @endisset
     </form>
@endsection

@section('scripts')
     <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
     <script>
     document.addEventListener('DOMContentLoaded', function () {
               const quill = new Quill('#editor', {
                    theme: 'snow',
                    readOnly: {{ isset($readOnly) ? 'true' : 'false' }}
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
               const newLabelSection = document.getElementById('new-label-section');

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
                                   newLabel.classList.add('dropdown-item');
                                   newLabel.innerHTML = `
                                        <input type="checkbox" id="label-${data.label.id}" name="labels[]" value="${data.label.id}" checked>
                                        <label for="label-${data.label.id}">${data.label.name}</label>
                                   `;
                                   newLabelSection.insertAdjacentElement('beforebegin', newLabel);
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

               // Dropdown toggle functionality
               const dropdownToggle = document.getElementById('label-dropdown-toggle');
               const dropdownMenu = document.getElementById('label-dropdown-menu');

               dropdownToggle.addEventListener('click', function () {
                    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
               });

               document.addEventListener('click', function (event) {
                    if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                         dropdownMenu.style.display = 'none';
                    }
               });
          });
          });
     </script>
     @include('entries.components.alerts-js')
@endsection
