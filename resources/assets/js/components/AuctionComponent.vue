<template>
    <div class="col-sm-4 text-center mb-3">
        <h5 v-if="xGame.bid_side == 'home'">Auction for {{ xGame.home_team.nickname }}</h5>
        <h5 v-if="xGame.bid_side == 'away'">Auction for {{ xGame.away_team.nickname }}</h5>
        <p class="text-small mb-0">Highest bid:</p>
        <p>{{ xGame.high_bidder_name }}: £{{ numberWithCommas(xGame.high_bid_amount) }}</p>
        <button
            v-if="xGame.owner"
            class="btn btn-primary btn-lg"
            @click="handler">
            Close auction
        </button>
        <p v-else class="dealer-status">Waiting for dealer...</p>
    </div>
</template>

<script>
    export default {
        computed: {
            xGame: function() {
                return this.$store.getters.GET_GAME;
            }
        },
        methods: {
            handler() {
                axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/close-auction').then(response => {
                    console.log(response);
                });
            }
        }
    };
</script>