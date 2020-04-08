<template>

    <div class="row no-gutters">
        <div class="col-sm-8">
            <div class="row">
                <div class="col-8">
                    <h4 class="mb-0">{{ game.match.home_allocation.team.id }} {{ game.match.home_allocation.team.nickname }}</h4>
                    <p class="small">{{ game.match.home_allocation.player.user.name }}</p>
                </div>
                <div class="col-4">
                    <h1>{{ game.match.home_score }}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <h4 class="mb-0">{{ game.match.away_allocation.team.id }} {{ game.match.away_allocation.team.nickname }}</h4>
                    <p class="small">{{ game.match.away_allocation.player.user.name }}</p>
                </div>
                <div class="col-4">
                    <h1>{{ game.match.away_score }}</h1>
                </div>
            </div>

        </div>

        <div class="col-sm-4 text-center mt-3 mb-3">
            <button
                v-if="owner"
                @click="nextDrawHandler" class="btn btn-primary btn-lg">
                Next
            </button>
            <p v-else class="dealer-status">Waiting for dealer...</p>
        </div>
    </div>
</template>

<script>
    export default {
        props:['game', 'nextDrawHandler', 'owner'],
    }
</script>
