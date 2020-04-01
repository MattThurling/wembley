<template>
    <div>
        <round-component
            v-if="tournament.phase == 'round'"
            :startRoundHandler="startRound"/>
        <draw-component
            v-if="tournament.phase == 'draw'"
            :tournament="tournament"
            :playMatchHandler="playMatch"/>
        <match-component v-if="tournament.phase == 'match'" />
        <chat-component :tournament_id="tournament_id" :user="user" />
        <teams-component :allocations="tournament.allocations" />
    </div>

</template>

<script>

    import RoundComponent from './RoundComponent.vue';
    import DrawComponent from './DrawComponent.vue';
    import MatchComponent from './MatchComponent.vue';
    import ChatComponent from './ChatComponent.vue';
    import TeamsComponent from './TeamsComponent.vue';
    export default {
        components: {
            RoundComponent,
            DrawComponent,
            MatchComponent,
            ChatComponent,
            TeamsComponent,
        },
        props:['tournament_id', 'user'],
        data() {
           return {
               tournament: {}
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
                axios.get('/api/tournament/' + this.tournament_id + '/match').then(response => {
                    this.tournament = response.data;
                })
            }
        }
    }
</script>
