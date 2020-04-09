<template>
    <div class="row no-gutters">

        <div class="col-sm-8">
            <div class="row">
                <div class="col-6">
                    <h4 class="mb-0">{{ game.home_team.id }} {{ game.home_team.nickname }}</h4>
                    <p v-if="game.home_user" class="small">{{ game.home_user.name }}</p>
                    <p v-else class="small">FOR SALE</p>
                </div>
                <div v-if="game.bid_side == 'home'" class="col-6">
                    <div class="input-group input-group-sm mb-3 mr-2">
                        <input type="text" class="form-control" v-model="amount">
                        <div class="input-group-append mr-3">
                            <button
                                class="btn btn-outline-primary"
                                @click="submitBid">
                                Bid
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <h4 class="mb-0">{{ game.away_team.id }} {{ game.away_team.nickname }}</h4>
                    <p v-if="game.away_user" class="small">{{ game.away_user.name }}</p>
                    <p v-else class="small">FOR SALE</p>
                </div>
                
                 <div v-if="game.bid_side == 'away'" class="col-6">
                    <div class="input-group input-group-sm mr-2">
                        <input type="text" class="form-control" v-model="amount">
                        <div class="input-group-append mr-3">
                            <button
                                class="btn btn-outline-primary"
                                @click="submitBid">
                                Bid
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-sm-4 text-center mb-3">
            <h5 v-if="game.bid_side == 'home'">Auction for {{ game.home_team.nickname }}</h5>
            <h5 v-if="game.bid_side == 'away'">Auction for {{ game.away_team.nickname }}</h5>
            <p class="text-small mb-0">Highest bid:</p>
            <p>{{ game.high_bidder_name }}: £{{highBid}}</p>
            <button
                v-if="game.owner"
                class="btn btn-primary btn-lg"
                @click="closeHandler">
                Close auction
            </button>
            <p v-else class="dealer-status">Waiting for dealer...</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['game', 'bidHandler', 'highBid', 'closeHandler'],
        data() {
            return {
                amount: 0
            }
        },
        methods: {
            submitBid() {
                this.bidHandler(this.amount);
                this.amount = 0;
            }
        }
    }
</script>
