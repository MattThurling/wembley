<template>
    <div class="row no-gutters">

        <div class="col-md-6">
            <div class="row">
                <div class="col-10">
                    <h4>{{ tournament.id }}</h4>
                    <p class="small">{{ tournament.id }}</p>
                </div>
                <div class="col-2">
                    <h1>{{ tournament.id }}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-10">
                    <h4>{{ tournament.id }}</h4>
                    <p class="small">{{ tournament.id }}</p>
                </div>
                <div class="col-2">
                    <h1>{{ tournament.id }}</h1>
                </div>
            </div>

        </div>


        <div class="col-md-6 text-center mt-3 mb-3">
            <form method="POST" @action="playMatch">
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
