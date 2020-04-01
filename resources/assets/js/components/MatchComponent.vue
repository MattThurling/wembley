<template>
    <div class="row no-gutters">

        <div class="col-md-6">
            <div class="row">
                <div class="col-10">
                    <h4>{{ tournament.home.name }}</h4>
                    <p class="small">{{ tournament.home_allocation.player.user.name }}</p>
                </div>
                <div class="col-2">
                    <h1 v-if="tournament.match">{{ tournament.match.home_score }}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-10">
                    <h4>{{ tournament.away.name }}</h4>
                    <p class="small">{{ tournament.away_allocation.player.user.name }}</p>
                </div>
                <div class="col-2">
                    <h1 v-if="tournament.match">{{ tournament.match.away_score }}</h1>
                </div>
            </div>

        </div>

        <div class="col-md-6 text-center mt-3 mb-3">
            <button v-if="tournament.match != null" @click="playMatch" class="btn btn-success btn-lg">Play</button>
            <div v-else>
                <button @click="startDraw" class="btn btn-success btn-lg">Start</button>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        props:['tournament_id'],
        data() {
            return {
                tournament: {},
            }
        },
        created() {
            this.getTournament();
        },
        methods: {
            getTournament() {
                axios.get('/api/tournament/' + this.tournament_id).then(response => {
                    this.tournament = response.data;
                })
            },
            playMatch() {
                console.log('Play match');
            },
            startDraw() {
                console.log('Start round');
                axios.post('/api/tournament/' + this.tournament_id + '/round').then(response => {
                    console.log(response.data);
                })
            },
        }
    }
</script>
