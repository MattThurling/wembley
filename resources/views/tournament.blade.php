@extends('layouts.app')

@section('content')

<div class="container">

  Tournament

  <match-component :tournament_id="'{{ $tournament->id }}'" />
  <teams-component :allocations="{{ $allocations }}" />
  <chat-component
    :player="{{ $player }}"
    :user="{{ Auth::user() }}"
    :tournament="{{ $tournament }}">
  </chat-component>

  @include('partials.bank')

  

</div>
@endsection