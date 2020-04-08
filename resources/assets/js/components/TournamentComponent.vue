<template>
    <div>
        <details-component
            v-if="game.phase != 'round'"
            :game="game"
            :winGate="numberWithCommas(2*game.home_team.gate/3)"
            :loseGate="numberWithCommas(game.home_team.gate/3)"/>
        <round-component
            v-if="game.phase == 'round'"
            :owner="game.owner"
            :startRoundHandler="startRound"/>
        <bid-component
            v-if="game.phase == 'bid'"
            :owner="game.owner"
            :game="game"
            :highBid="numberWithCommas(game.high_bid_amount)"
            :bidHandler="bid"
            :closeHandler="closeAuction"/>
        <draw-component
            v-if="game.phase == 'draw'"
            :owner="game.owner"
            :game="game"
            :playMatchHandler="playMatch"/>
        <redraw-component
            v-if="game.phase == 'redraw'"
            :owner="game.owner"
            :game="game"
            :redrawHandler="redraw"/>
        <match-component
            v-if="game.phase == 'match'"
            :owner="game.owner"
            :game="game"
            :next-draw-handler="nextDraw"/>
        <chat-component :tournament_id="tournament_id" :user="user" />
        <div class="row">
            <teams-component
                :allocations="game.allocations" />
            <bank-component
                :balance="numberWithCommas(game.player.balance)" />
        </div>
    </div>

</template>

<script>

    import RoundComponent from './RoundComponent.vue';
    import DrawComponent from './DrawComponent.vue';
    import MatchComponent from './MatchComponent.vue';
    import ChatComponent from './ChatComponent.vue';
    import TeamsComponent from './TeamsComponent.vue';
    import DetailsComponent from './DetailsComponent.vue';
    import BankComponent from './BankComponent.vue';
    import RedrawComponent from './RedrawComponent.vue';
    import BidComponent from './BidComponent.vue';
    export default {
        components: {
            RoundComponent,
            DrawComponent,
            MatchComponent,
            ChatComponent,
            TeamsComponent,
            BankComponent,
            DetailsComponent,
            BidComponent,
            RedrawComponent,
        },
        props:['tournament_id', 'user'],
        data() {
           return {
                bidAmount: 0,
                game: {
                    home_team: {gate: 0},
                    player: {balance:0},
                    round: {},
                    bid: {
                        high_bid: {
                            player: {
                                user: {}
                            }
                        }
                    }
                }
            }
        },
        created() {
            this.getTournament();
            Echo.join(this.tournament_id)
                .listen('UpdateTournamentEvent',(event) => {
                    console.log('LISTEN: ' + JSON.stringify(event));
                    this.getTournament();
                })
        },
        methods: {
            getTournament() {
                axios.get('/api/tournament/' + this.tournament_id).then(response => {
                    this.game = response.data;
                })
            },
            startRound() {
                axios.post('/api/tournament/' + this.tournament_id + '/round').then(response => {
                    this.game = response.data;
                })
            },
            playMatch() {
                axios.post('/api/tournament/' + this.tournament_id + '/match').then(response => {
                    this.game = response.data;
                })
            },
            nextDraw() {
                axios.post('/api/tournament/' + this.tournament_id + '/next').then(response => {
                    this.game = response.data;
                })
            },
            redraw(side) {
                axios.post('/api/tournament/' + this.tournament_id + '/redraw', {side: side}).then(response => {
                    console.log(response);

                })
            },
            bid(amount) {
                axios.post('/api/tournament/' + this.tournament_id + '/bid',
                    {
                        amount: amount,
                        side: this.game.bid_side,
                    })
                    .then(response => {
                        console.log(response);
                    })
                    .catch(err => {
                        alert('Invalid bid');
                    })
            },
            closeAuction() {
                axios.post('/api/tournament/' + this.tournament_id + '/close-auction').then(response => {
                    console.log(response);
                })
            },
            numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
        }
    }
</script>
