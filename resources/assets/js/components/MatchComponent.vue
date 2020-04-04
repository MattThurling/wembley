<template>

    <div class="row no-gutters">

        <div class="col-sm-8">
            <div class="row">
                <div class="col-8">
                    <h4 class="mb-0">{{ tournament.match.home_allocation.team.name }}</h4>
                    <p class="small">{{ tournament.match.home_allocation.player.user.name }}</p>
                </div>
                <div class="col-4">
                    <h1>{{ tournament.match.home_score }}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <h4 class="mb-0">{{ tournament.match.away_allocation.team.name }}</h4>
                    <p class="small">{{ tournament.match.away_allocation.player.user.name }}</p>
                </div>
                <div class="col-4">
                    <h1>{{ tournament.match.away_score }}</h1>
                </div>
            </div>

        </div>

        <div class="col-sm-4 text-center mt-3 mb-3">
            <button
                v-if="owner"
                @click="nextDrawHandler" class="btn btn-success btn-lg">
                Next
            </button>
            <p v-else class="text-muted">Waiting for dealer...</p>
        </div>
    </div>
</template>

<script>
    export default {
        props:['tournament', 'nextDrawHandler', 'owner'],
    }
</script>
