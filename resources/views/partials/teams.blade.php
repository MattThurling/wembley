<div class="card mt-3">
    <div class="card-header">
        <div class="card-title">
            <h5 class="card-text">Your teams</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="card-text">
            @foreach ($player->allocations as $allocation)
                @switch ($allocation->status)
                    @case(1)
                        <p class="text-success">
                        @break
                    @case(-1)
                        <p class="text-danger">
                        @break
                    @default
                        <p>
                @endswitch
                            {{ $allocation->team->name }}
                        </p>
            @endforeach
        </div>
    </div>
</div>