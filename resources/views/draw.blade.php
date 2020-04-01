@extends('layouts.app')

@section('content')

  <div class="container">

    <draw-component :tournament="{{ $tournament }}" :allocations="{{ $allocations }}"/>

  </div>

@endsection