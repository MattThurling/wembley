<template>

    <div class="card auction">
        <div class="card-body text-center">
            <h5 class="card-title mb-0" v-if="xGame.bid_side == 'home'">Auction for {{ xGame.home_team.name }}</h5>
            <h5 class="card-title mb-0" v-if="xGame.bid_side == 'away'">Auction for {{ xGame.away_team.name }}</h5>
            <p class="card-text mb-0">
               <span class="small">Highest bid:</span> {{ xGame.high_bidder_name }}</p>

            <transition
                name="fade"
                enter-active-class="animated bounceIn">
                <h3 :key="xGame.high_bid_amount">£{{ numberWithCommas(xGame.high_bid_amount) }}</h3>
            </transition>
        </div>
    </div>

</template>

<script>
    export default {
        computed: {
            xGame: function() {
                return this.$store.getters.GET_GAME;
            }
        },
    };
</script>