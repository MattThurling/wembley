<template>
    <div class="row no-gutters">

        <div class="col-sm-8">
            <div class="row">
                <div class="col-8">
                    <h4 class="mb-0">{{ game.home_team.nickname }}</h4>
                    <p class="small">{{ game.home_user.name }}</p>
                </div>
                <div class="col-4">

                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <h4 class="mb-0">{{ game.away_team.nickname }}</h4>
                    <p class="small">{{ game.away_user.name }}</p>
                </div>
                <div class="col-4">

                </div>
            </div>

        </div>

        <div class="col-sm-4 text-center mt-3 mb-3">
            <button
                v-if="owner"
                @click="playMatchHandler" class="btn btn-primary btn-lg">
                Play
            </button>
            <p v-else class="dealer-status">Waiting for dealer...</p>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['game', 'playMatchHandler', 'owner']
    }

</script>
