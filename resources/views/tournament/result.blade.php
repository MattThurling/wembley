@extends('tournament.base')

@section('controls')
  <p>
    {{ $round->name . ' | Match ' . $round->position . ' of ' . $round->number_of_matches }}
  </p>
  <div class="row no-gutters">

    <div class="col-md-6">
        <div class="row">
            <div class="col-10">
                <h4>{{ $match->home_allocation->team->name }}</h4>
                <p class="small">{{ $match->home_allocation->player->user->name }}</p>
            </div>
            <div class="col-2">
                <h1>{{ $match->home_score }}</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-10">
                <h4>{{ $match->away_allocation->team->name }}</h4>
                <p class="small">{{ $match->away_allocation->player->user->name }}</p>
            </div>
            <div class="col-2">
                <h1>{{ $match->away_score }}</h1>
            </div>
        </div>

      </div>


      <div class="col-md-6 text-center mt-3 mb-3">
          <form method="POST" action="{{ url()->current() . '/next' }}">
              @csrf
              <button type="submit" class="btn btn-success btn-lg">Next</button>
          </form>
      </div>
  </div>

@endsection