<template>
    <div>
        <div class="row no-gutters">

            <div class="col-sm-8">
                <h4>Welcome! You're on your way to Wembley</h4>
                <p>You are the millionaire owner of not one but many teams competing for the cup. Your teams, their status in the tournament, and the gate money they generate when they play at home are shown below.</p>
                <p v-if="owner">As the dealer, you are responsible for starting each round.</p>
                <p>Feel free to bait your rivals in the live stream, below.</p>
            </div>
            <div class="col-sm-4 mt-3 text-center mb-3">
                <button
                    v-if="owner"
                    @click="startRoundHandler" class="btn btn-success btn-lg">
                    Start
                </button>
                <p v-else class="text-muted">Waiting for dealer...</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['startRoundHandler', 'owner']
    }
</script>
