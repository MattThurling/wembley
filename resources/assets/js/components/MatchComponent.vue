<template>
    <div>
        <p>
            {{ tournament.round.name }} | Match {{ tournament.round.position }} of {{ tournament.round.number_of_matches }}
        </p>
        <div class="row no-gutters">

            <div class="col-md-6">
                <div class="row">
                    <div class="col-10">
                        <h4>{{ tournament.match.home_allocation.team.name }}</h4>
                        <p class="small">{{ tournament.match.home_allocation.player.user.name }}</p>
                    </div>
                    <div class="col-2">
                        <h1>{{ tournament.match.home_score }}</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-10">
                        <h4>{{ tournament.match.away_allocation.team.name }}</h4>
                        <p class="small">{{ tournament.match.away_allocation.player.user.name }}</p>
                    </div>
                    <div class="col-2">
                        <h1>{{ tournament.match.away_score }}</h1>
                    </div>
                </div>

            </div>

            <div class="col-md-6 text-center mt-3 mb-3">
                <button @click="nextDrawHandler" class="btn btn-success btn-lg">Next</button>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props:['tournament', 'nextDrawHandler'],
    }
</script>
