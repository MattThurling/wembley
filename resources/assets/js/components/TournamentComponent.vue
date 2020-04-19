<template>
    <div>
        <win-component v-if="$store.getters.GET_GAME.phase == 'complete'"/>
        <template v-else>
            <round-component v-if="$store.getters.GET_GAME.phase == 'round'"/>
            <template v-else>
                <div class="row">
                    <div class="col-8">
                        <details-component />
                    </div>
                    <div class="col-4 text-center">
                        <control-component />
                    </div>
                </div>
                <play-component />
            </template>
            <chat-component :tournament_id="tournament_id" :user="user" />
            <div class="row">
                <teams-component />
                <bank-component />
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <results-component />
                </div>
            </div>
        </template>
    </div>

</template>

<script>
    import RoundComponent from './RoundComponent.vue';
    import ChatComponent from './ChatComponent.vue';
    import TeamsComponent from './TeamsComponent.vue';
    import DetailsComponent from './DetailsComponent.vue';
    import BankComponent from './BankComponent.vue';
    import PlayComponent from './PlayComponent.vue';
    import ResultsComponent from './ResultsComponent.vue'
    import WinComponent from './WinComponent.vue'
    export default {
        components: {
            RoundComponent,
            ChatComponent,
            TeamsComponent,
            BankComponent,
            DetailsComponent,
            PlayComponent,
            ResultsComponent,
            WinComponent,
        },
        props:['tournament_id', 'user'],
        mounted() {
            this.$store.dispatch("SET_TOURNAMENT_ID", this.tournament_id);
            this.$store.dispatch("UPDATE_TOURNAMENT", this.$store.getters.GET_TOURNAMENT_ID);
            // Subscribe this component to any tournament updates from other players
            Echo.join(this.tournament_id)
                .listen('UpdateTournamentEvent',(event) => {
                    console.log('LISTEN: ' + JSON.stringify(event));
                    this.$store.dispatch("UPDATE_TOURNAMENT", this.$store.getters.GET_TOURNAMENT_ID);
                })
        },
        methods: {

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
            sell(id) {
                axios.post('/api/tournament/' + this.tournament_id + '/sell', {team_id: id}).then(response => {
                    console.log(response);

                })
            },
        }
    };
</script>
