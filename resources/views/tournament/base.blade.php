@extends('layouts.app')

@section('content')
<div class="container">

    @yield('controls')

    <chat-component :user="{{ auth()->user() }}"></chat-component>

    @include('partials.bank')

    @include('partials.teams')

</div>
@endsection