<template>
    <div class="row no-gutters">

        <div class="col-sm-8">
            <div class="row">
                <div class="col-8">
                    <h4 class="mb-0">{{ tournament.home_team.name }}</h4>
                    <p class="small">{{ tournament.home_user.name }}</p>
                </div>
                <div class="col-4">

                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <h4 class="mb-0">{{ tournament.away_team.name }}</h4>
                    <p class="small">{{ tournament.away_user.name }}</p>
                </div>
                <div class="col-4">

                </div>
            </div>

        </div>

        <div class="col-sm-4 text-center mt-3 mb-3">
            <button
                v-if="owner"
                @click="playMatchHandler" class="btn btn-success btn-lg">
                Play
            </button>
            <p v-else class="text-muted">Waiting for dealer...</p>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['tournament', 'playMatchHandler', 'owner']
    }

</script>
