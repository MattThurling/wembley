<template>
    <div>
        <p>
            {{ tournament.round.name }} | Match {{ tournament.round.position }} of {{ tournament.round.number_of_matches }}
        </p>
        <div class="row no-gutters">

            <div class="col-sm-8">
                <div class="row">
                    <div class="col-8">
                        <h4>{{ tournament.home_team.name }}</h4>
                        <p class="small">{{ tournament.home_user.name }}</p>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-outline-primary btn-sm">Play</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <h4>{{ tournament.away_team.name }}</h4>
                        <p class="small">{{ tournament.away_user.name }}</p>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-outline-primary btn-sm">Play</button>
                    </div>
                </div>

            </div>

            <div class="col-sm-4 text-center mt-3 mb-3">
                <button
                    v-if="owner"
                    @click="playMatchHandler" class="btn btn-success btn-lg">
                    Play
                </button>
                <p v-else class="text-muted">Waiting for player...</p>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        props: ['tournament', 'playMatchHandler', 'owner']
    }

</script>
