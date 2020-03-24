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
                    <p class="card-text small">{{ $tournament->owner->created_at }}</p>
                    @if ($tournament->owner->id == Auth::id())
                        <form method="POST" action="tournament/{{ $tournament->id }}/start">
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-outline-success btn-sm"
                                {{ (count($tournament->players) < 2) ? 'disabled' : ''}}>
                                Start
                            </button>
                        </form>
                    @else
                        <form method="POST" action="tournament/{{ $tournament->id }}/join">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                Join
                            </button>
                        </form>
                    @endif
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
