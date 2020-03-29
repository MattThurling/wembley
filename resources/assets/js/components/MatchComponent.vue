<template>
    <div class="row no-gutters">

        <div class="col-md-6">
            <div class="row">
                <div class="col-10">
                    <h4>{{ home.name }}</h4>
                    <p class="small">{{ home }}</p>
                </div>
                <div class="col-2">
                    <h1>{{ home.score }}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-10">
                    <h4>{{ away.name }}</h4>
                    <p class="small">{{ away }}</p>
                </div>
                <div class="col-2">
                    <h1>{{ away.score }}</h1>
                </div>
            </div>

        </div>


        <div class="col-md-6 text-center mt-3 mb-3">
            <form method="POST" @action="playMatch">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">Play</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props:['tournament'],
        data() {
            return {
                tournament: {},
                round: {},
                match: {},
            }
        },
        created() {
            this.getTournament();
        },
        methods: {
            getTournament() {
                axios.get('/api/' + '/tournament/' + this.tournament.id).then(response => {
                    this.messages = response.data;
                })
            },
            playMatch() {
                console.log('Play match');
            },
        }
    }
</script>
