<template>
    <div>
        <div class="row no-gutters">

            <div class="col-sm-8">
                <h4>Welcome! You're on your way to Wembley</h4>
                <p>You are the millionaire owner of not one but many teams competing for the cup. Your teams, their status in the tournament, and the gate money they generate when they play at home are shown below.</p>
                <p v-if="$store.getters.GET_GAME.owner">As the dealer, you are responsible for starting each round.</p>
                <p>Feel free to bait your rivals in the live stream, below.</p>
            </div>
            <div class="col-sm-4 mt-3 text-center mb-3">
                <button
                    v-if="$store.getters.GET_GAME.owner"
                    @click="startRound" class="btn btn-primary btn-lg">
                    Start
                </button>
                <p v-else class="dealer-status">Waiting for dealer...</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            startRound() {
                axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/round').then(response => {
                    console.log(response.data);
                })
            }
        }
    };
</script>
