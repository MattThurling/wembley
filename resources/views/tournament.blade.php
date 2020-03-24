@extends('layouts.app')

@section('content')
<div class="container">
    
   <p>Fourth Round | Match 1 of 16</p>
    <div class="row no-gutters">
       <div class="col-md-6">
            <div class="row">
                <div class="col-10">
                    <h4>Tottenham Hotspur</h4>
                    <p class="small">Matt Thurling</p>
                </div>
                <div class="col-2">
                    <h1>5</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-10">
                    <h4>Manchester City</h4>
                    <p class="small">Eric Dolphy</p>
                </div>
                <div class="col-2">
                    <h1>2</h1>
                </div>
            </div>

        </div>

        <div class="col-md-6 text-center mt-3 mb-3">
            <form method="POST" action="{{ url()->current() . '/round' }}">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">Start</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-text">
                <p>Bank balance: £5,000</p>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-text">Your teams</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="card-text">
                @foreach ($player->allocations as $allocation)
                    <p>{{ $allocation->team->name }}</p>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
