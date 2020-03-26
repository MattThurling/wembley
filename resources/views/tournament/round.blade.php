@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row no-gutters">

        <div class="col-md-6 text-center mt-3 mb-3">
            <form method="POST" action="{{ url()->current() . '/round' }}">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">Start</button>
            </form>
            
        </div>
    </div>

    @include('partials.bank')

    @include('partials.teams')

</div>
@endsection