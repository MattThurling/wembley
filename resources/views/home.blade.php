@extends('layouts.app')

@section('content')
<div class="container">
    
    <h1>Tournaments</h1>
    
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="card-deck">
        @foreach ($tournaments as $tournament)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ $tournament->owner->name }}</h5>
                    <p class="card-text small">{{ $tournament->created_at }}</p>
                    @switch ($tournament->status)
                        @case(0)
                            @include('partials.open')
                            @break
                        @case(1)
                            @include('partials.live')
                            @break
                        @case(-1)
                            @include('partials.complete')
                            @break
                    @endswitch
                </div>

                <div class="card-body">

                    @foreach ($tournament->players as $player)
                        <p class="card-text">{{ $player->user->name }}</p>
                    @endforeach

                    
                </div>

            </div>
        @endforeach
    </div>
    <div class="mt-3">
        <form method="POST" action="tournament">
            @csrf
            <button type="submit" class="btn btn-primary">Create new tournament</button>
        </form>
    </div>

</div>
@endsection
