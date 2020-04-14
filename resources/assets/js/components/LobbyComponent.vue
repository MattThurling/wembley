<template>
    <div>
        <div class="row">
            <div class="col-sm-4" v-for="tournament in tournaments.data">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title">{{ tournament.owner.name }}</h5>
                        <p class="card-text small">{{ tournament.created_at }}</p>
                    </div>

                    <div class="card-body">

                        <p v-for="player in tournament.players" class="card-text">
                            {{ player.user.name }}
                        </p>

                        <div v-if="tournament.status == 0">

                            <div v-if="tournament.owner.id == user.id">

                                <button
                                    v-if="tournament.players.length > 1"
                                    @click="doTournament(tournament.id, 'POST', 'start')"
                                    class="btn btn-outline-success btn-sm"
                                    :disabled="tournament.players.length < 2">
                                    Start
                                </button>
                                <p v-else class="text-muted">Waiting for more players...</p>
                            </div>

                            <div v-else>

                                <button
                                    @click="doTournament(tournament.id, 'POST', 'join')"
                                    class="btn btn-outline-primary btn-sm">
                                    Join
                                </button>

                            </div>
                        </div>

                        <div v-else>
                            <a class="btn btn-outline-primary btn-sm" :href="'tournament/' + tournament.id">
                                Continue
                            </a>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        <div class="mt-3">
            <button @click="createTournament" class="btn btn-outline-primary">Create new tournament</button>
        </div>
    </div>

</template>

<script>
    export default {
        props:['user'],
        data() {
            return {
                tournaments: []
            }
        },
        created() {
            this.getTournaments();
            Echo.join('lobby')
                .here(user => {
                    this.users = user;
                })
                .joining(user => {
                    this.users.push(user);
                })
                .leaving(user => {
                    this.users = this.users.filter(u => u.id != user.id);
                })
                .listen('TournamentJoined',(event) => {
                    this.getTournaments();
                })
                .listen('TournamentStarted',(event) => {
                    console.log('Started: ' + JSON.stringify(event.tournament));
                    this.goToTournament(event.tournament.id);
                })
        },
        methods: {
            getTournaments() {
                axios.get('/api/tournament').then(response => {
                    this.tournaments = response.data;
                })
            },
            createTournament() {
                axios.post('/api/tournament').then(response => {
                    console.log(response.data);
                })
            },
            doTournament(id, method, verb) {
                console.log(verb);
                let config = {
                    method: method,
                    url: '/api/tournament/' + id + '/' + verb
                };
                axios(config).then(response => {
                    console.log(response);
                })
            },
            goToTournament(id) {
                
                axios.get('/api/tournament/' + id + '/goto').then(response => {
                    if (response.data.message) window.location = "/tournament/" + id;
                });
            }
        }
    }
</script>
