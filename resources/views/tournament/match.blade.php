@extends('tournament.base')

@section('controls')
  <match-component :tournament="{{ $tournament }}" />

@endsection