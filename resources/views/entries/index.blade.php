@extends('layouts.template')

@section('title', isset($title) ? $title : 'Journal')

@section('content')

     @include('components/header', [$user, $entries])

     @isset($search)
          @if($entries->count() > 0)
               <h2 class="title-result">Results for: "{{ $search }}"</h2>
          @endif
     @endisset

     <section class="entries-section grid" id="entries-section">
          @foreach ($entries as $entry)
               @include('components/card', $entry)
          @endforeach

          @if($entries->count() == 0)
               @isset($search)
                    <h2 class="no-entries">No matches found. Try a different search...</h2>
               @else
                    <h2 class="no-entries">There are no entries...</h2>  
               @endisset
          @endif
     </section>

     {{-- Floating Action Button --}}
     <a href="{{ route('entries.create') }}" class="fab" role="button" style="
          position: fixed;
          bottom: 20px;
          right: 20px;
          width: 60px;
          height: 60px;
          background-color: rgb(0, 0, 0);
          color: white;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
          font-size: 36px;
          cursor: pointer;
          text-decoration: none;
          z-index: 1000;
          transition: transform 0.2s ease-in-out;">
          <span class="material-icons-outlined">add</span>
     </a>

@endsection

@section('styles')
     {{-- Removed centralized FAB styles --}}
@endsection

@section('scripts')
     <script src="{{ asset("/js/home.js") }}"></script>
     @include('entries.components.alerts-js')
     @include('components.alert-label')

     @isset($trash)
          @include('entries.components.alert-empty-trash')
     @endisset
@endsection