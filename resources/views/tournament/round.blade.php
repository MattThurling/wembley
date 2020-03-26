@extends('tournament.base')

@section('controls')
    <div class="row no-gutters">
        <div class="col-md-6 text-center mt-3 mb-3">
            <form method="POST" action="{{ url()->current() . '/round' }}">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">Start</button>
            </form>
            
        </div>
    </div>
@endsection