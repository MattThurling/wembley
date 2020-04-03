<template>
    <div>
        <round-component
            v-if="tournament.phase == 'round'"
            :owner="tournament.owner"
            :startRoundHandler="startRound"/>
        <draw-component
            v-if="tournament.phase == 'draw'"
            :owner="tournament.owner"
            :tournament="tournament"
            :playMatchHandler="playMatch"/>
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
                :player="tournament.player" />
        </div>
    </div>

</template>

<script>

    import RoundComponent from './RoundComponent.vue';
    import DrawComponent from './DrawComponent.vue';
    import MatchComponent from './MatchComponent.vue';
    import ChatComponent from './ChatComponent.vue';
    import TeamsComponent from './TeamsComponent.vue';
    import BankComponent from './BankComponent.vue';
    export default {
        components: {
            RoundComponent,
            DrawComponent,
            MatchComponent,
            ChatComponent,
            TeamsComponent,
            BankComponent,
        },
        props:['tournament_id', 'user'],
        data() {
           return {
               tournament: {}
           }
        },
        created() {
            this.getTournament();
            Echo.join(this.tournament_id)
                .listen('TournamentRoundCreated',(event) => {
                    console.log('LISTEN: ' + JSON.stringify(event));
                    this.getTournament();
                })
                .listen('TournamentMatchCreated',(event) => {
                    console.log('LISTEN: ' + JSON.stringify(event));
                    this.getTournament();
                })
                .listen('TournamentNext',(event) => {
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
        }
    }
</script>
