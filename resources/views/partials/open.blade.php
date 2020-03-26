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