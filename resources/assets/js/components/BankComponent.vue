<template>
    <div class="col-sm-8 col-md-4 mt-3">
        <h6 class="text-center">BANK BALANCE</h6>
        <div class="card">
            <div class="card-body">
                <div class="card-text">
                    <h4 class="text-center text-responsive">£{{ numberWithCommas($store.getters.GET_GAME.player.balance) }}</h4>
                </div>
            </div>
        </div>
    </div>
</template>

