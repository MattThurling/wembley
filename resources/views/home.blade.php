@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Tournaments</h1>
        <p>This is a game for 2-12 players. Join an existing tournament or create your own!</p>

        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif


        <lobby-component :user="{{ auth()->user() }}" />

    </div>

@endsection
