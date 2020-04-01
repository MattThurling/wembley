@extends('layouts.app')

@section('content')

<div class="container">

  <tournament-component
    :player="{{ $player }}"
    :user="{{ Auth::user() }}"
    :tournament_id="'{{ $tournament->id }}'">
  </tournament-component>

  @include('partials.bank')

</div>
@endsection