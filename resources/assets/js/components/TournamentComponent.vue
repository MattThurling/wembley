<template>
    <div>
        <details-component
            v-if="tournament.phase != 'round'"
            :tournament="tournament"
            :winGate="numberWithCommas(80*tournament.home_team.gate)"
            :loseGate="numberWithCommas(40*tournament.home_team.gate)"/>
        <round-component
            v-if="tournament.phase == 'round'"
            :owner="tournament.owner"
            :startRoundHandler="startRound"/>
        <draw-component
            v-if="tournament.phase == 'draw'"
            :owner="tournament.owner"
            :tournament="tournament"
            :playMatchHandler="playMatch"/>
        <redraw-component
            v-if="tournament.phase == 'redraw'"
            :owner="tournament.owner"
            :tournament="tournament"
            :redrawHandler="redraw"/>
        <match-component
            v-if="tournament.phase == 'match'"
            :owner="tournament.owner"
            :tournament="tournament"
            :next-draw-handler="nextDraw"/>
        <chat-component :tournament_id="tournament_id" :user="user" />
        <div class="row">
            <teams-component
                :allocations="tournament.allocations" />
            <bank-component
                :balance="numberWithCommas(120*tournament.player.balance)" />
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
    export default {
        components: {
            RoundComponent,
            DrawComponent,
            MatchComponent,
            ChatComponent,
            TeamsComponent,
            BankComponent,
            DetailsComponent,
        },
        props:['tournament_id', 'user'],
        data() {
           return {
               tournament: {home_team: {gate: 0}, player: {balance:0}, round: {}}
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
                    this.tournament = response.data;
                })
            },
            startRound() {
                axios.post('/api/tournament/' + this.tournament_id + '/round').then(response => {
                    this.tournament = response.data;
                })
            },
            playMatch() {
                axios.post('/api/tournament/' + this.tournament_id + '/match').then(response => {
                    this.tournament = response.data;
                })
            },
            nextDraw() {
                axios.post('/api/tournament/' + this.tournament_id + '/next').then(response => {
                    this.tournament = response.data;
                })
            },
            redraw(side) {
                axios.post('/api/tournament/' + this.tournament_id + '/redraw', {side: side}).then(response => {
                    console.log(response);
                    // this.tournament = response.data;
                })
            },
            numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
        }
    }
</script>
