<template>
    <div>
        <p>
            {{ tournament.round.name }} | Match {{ tournament.round.position }} of {{ tournament.round.number_of_matches }}
        </p>
        <div class="row no-gutters">

            <div class="col-md-6">
                <div class="row">
                    <div class="col-10">
                        <h4>{{ tournament.home_team.name }}</h4>
                        <p class="small">{{ tournament.home_user.name }}</p>
                    </div>
                    <div class="col-2">

                    </div>
                </div>

                <div class="row">
                    <div class="col-10">
                        <h4>{{ tournament.away_team.name }}</h4>
                        <p class="small">{{ tournament.away_user.name }}</p>
                    </div>
                    <div class="col-2">

                    </div>
                </div>

            </div>

            <div class="col-md-6 text-center mt-3 mb-3">
                <button
                    v-if="owner"
                    @click="playMatchHandler" class="btn btn-success btn-lg">
                    Play
                </button>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        props: ['tournament', 'playMatchHandler', 'owner']
    }

</script>
