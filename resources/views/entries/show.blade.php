@extends('layouts.template')

@section('title', isset($title) ? $title : 'Note App')

@section('styles')
     <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')

     <a href="{{ isset($readOnly) ? route('entries.trash') : route('entries.index') }}" class="back-arrow">
          <span class="material-icons-outlined">&#xe5c4;</span> Back
     </a>

     <form action="{{ route('entries.update', $entry) }}" method="post" id="entry-form">
          @csrf
          @method('PUT')
          @isset($readOnly)
               <div class="options">
                    <button class=" button-icon" type="submit" disabled><span class="material-icons-outlined">&#xe161;</span> Save</button>
               </div>
               <div class="datetime-container">
                    <label for="entry-datetime" class="form-label">Date & Time:</label>
                    <input type="datetime-local" name="created_at" id="entry-datetime" class="datetime-input" value="{{ $entry->created_at->format('Y-m-d\TH:i') }}" readonly>
               </div>
          @else
               <div class="options">
                    <button class=" button-icon" type="submit"><span class="material-icons-outlined">&#xe161;</span> Save</button>
               </div>

               <div class="datetime-container">
                    <label for="entry-datetime" class="form-label">Date & Time:</label>
                    <input type="datetime-local" id="entry-datetime" class="datetime-input" value="{{ $entry->created_at->format('Y-m-d\TH:i') }}" readonly>
               </div>

               <div class="dropdown-emotions dropdown-editor">
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

               <input type="text" name="title" value="{{ old('title', $entry->title) }}" placeholder="Title" class="title-input" id="title-input" @isset($readOnly) readonly @endisset>
               @error('title')
                    <div class="error-message">{{ $message }}</div>
               @enderror

               <div class="editor-container">
                    <div id="editor">{!! old('body', $entry->body) !!}</div>
               </div>
               <textarea name="body" id="entry-body" class="entry-textarea" hidden>{{ old('body', $entry->body) }}</textarea>
               @error('body')
                    <div class="error-message">{{ $message }}</div>
               @enderror

               <div class="label-container">
                    <h3>Labels:</h3>
                    <div id="current-labels">
                         @foreach ($user->labels as $label)
                              <div class="label-item">
                                   <input type="checkbox" id="label-{{ $label->id }}" name="labels[]" value="{{ $label->id }}"
                                        {{ $entry->labels->contains($label->id) ? 'checked' : '' }}>
                                   <label for="label-{{ $label->id }}">{{ $label->name }}</label>
                              </div>
                         @endforeach
                    </div>
                    <div class="add-label-section">
                         <label for="new-label-input">Add New Label:</label>
                         <input type="text" id="new-label-input" name="new_label" placeholder="Enter label name" class="label-input">
                    </div>
               </div>
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

               // Set datetime-local field to entry's created_at time in user's timezone
               const datetimeInput = document.getElementById('entry-datetime');
               const createdAt = new Date("{{ $entry->created_at->toIso8601String() }}");
               const offset = createdAt.getTimezoneOffset() * 60000; // Offset in milliseconds
               const localISOTime = new Date(createdAt - offset).toISOString().slice(0, 16);
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
          });
     </script>
     @include('entries.components.alerts-js')
@endsection