@extends('tournament.base')

@section('controls')
  <p>
    {{ $round->name . ' | Match ' . $round->position . ' of ' . $round->number_of_matches }}
  </p>
  <div class="row no-gutters">

    <div class="col-md-6">
        <div class="row">
            <div class="col-10">
                <h4>{{ $home->name }}</h4>
                <p class="small">{{ $home->current_user($tournament)->first()->name }}</p>
            </div>
            <div class="col-2">
                <h1>{{ $match ?? '' ? $match->home_score : '' }}</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-10">
                <h4>{{ $away->name }}</h4>
                <p class="small">{{ $away->current_user($tournament)->first()->name }}</p>
            </div>
            <div class="col-2">
                <h1>{{ $match ?? '' ? $match->away_score : '' }}</h1>
            </div>
        </div>

      </div>


      <div class="col-md-6 text-center mt-3 mb-3">
          <form method="POST" action="{{ url()->current() . '/match' }}">
              @csrf
              <button type="submit" class="btn btn-success btn-lg">Play</button>
          </form>
      </div>
  </div>

@endsection