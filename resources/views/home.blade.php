@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Tournaments</h1>

        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif

        <lobby-component :user="{{ auth()->user() }}" />

    </div>

@endsection
