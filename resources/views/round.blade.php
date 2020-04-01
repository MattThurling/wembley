@extends('layouts.app')

@section('content')

  <div class="container">

    <round-component :tournament="{{ $tournament }}" :allocations="{{ $allocations }}" :user="{{ Auth::user() }}" />

  </div>

@endsection