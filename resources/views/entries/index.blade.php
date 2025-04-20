@extends('layouts.template')

@section('title', isset($title) ? $title : 'Journal')

@section('content')

<div style="border-radius: 10px; position: sticky; top: 0; z-index: 1000; background-color: #ffffff; align-items: center;">
     @include('components/header', [$user, $entries])
</div>

@isset($search)
     @if($entries->count() > 0)
          <h2 class="title-result">Results for: "<em>{{ $search }}</em> "</h2>
     @endif
@endisset

<section class="entries-section grid" id="entries-section">
     @foreach ($entries as $entry)
          @include('components/card', $entry)
     @endforeach

     @if($entries->count() == 0)
          @isset($search)
               <h2 class="no-entries" style="text-align: center; margin-top: 20px;">No matches found. Try a different search...</h2>
          @else
               <h2 class="no-entries" style="text-align: center; margin-top: 20px;">There are no entries...</h2>
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
          background-color: #3e3e3e;
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
          transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out;"
     onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#ff9900';"
     onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#3e3e3e';">
     <span class="material-icons-outlined">add</span>
</a>

@endsection

@section('styles')
<style>
     .body {
          background-color: #fff; /* Light gray background color */
          font-family: -apple-system, BlinkMacSystemFont, sans-serif;
     }

     .container {
          width: 80%;
     }
</style>
@endsection

<section class="entries-section grid" id="entries-section">
     @foreach ($entries as $entry)
          @include('components/card', $entry)
     @endforeach

     @if($entries->count() == 0)
          @isset($search)
               <h2 class="no-entries" style="text-align: center; margin-top: 20px;">No matches found. Try a different search...</h2>
          @else
               <h2 class="no-entries" style="text-align: center; margin-top: 20px;">There are no entries...</h2>
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
          background-color: #3e3e3e;
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
          transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out;"
     onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#ff9900';"
     onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#3e3e3e';">
     <span class="material-icons-outlined">add</span>
</a>

@endsection

@section('styles')
<style>
     .body {
          background-color: #fff; /* Light gray background color */
          font-family: -apple-system, BlinkMacSystemFont, sans-serif;
     }

     .container {
          width: 80%;
     }
</style>
@endsection

@section('scripts')
<script src="{{ asset('/js/home.js') }}"></script>
@include('entries.components.alerts-js')
@include('components.alert-label')

@isset($trash)
     @include('entries.components.alert-empty-trash')
@endisset
@endsection
