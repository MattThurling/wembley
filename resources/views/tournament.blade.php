@extends('layouts.app')

@section('content')

<div class="container">

  <tournament-component
    :user="{{ Auth::user() }}"
    :tournament_id="'{{ $tournament->id }}'">
  </tournament-component>

</div>
@endsection