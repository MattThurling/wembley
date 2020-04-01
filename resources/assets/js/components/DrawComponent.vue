<template>
    <div>
        <div class="row no-gutters">
            <div class="col-md-6 text-center mt-3 mb-3">
                <button @click="startDraw" class="btn btn-success btn-lg">Start</button>
            </div>
        </div>
        <chat-component :tournament="tournament" />
        <teams-component :allocations="teams" />
    </div>

</template>

<script>

    import TeamsComponent from './TeamsComponent.vue';
    export default {
        components: {
            TeamsComponent,
        },
        props:['tournament', 'allocations'],
        data() {
            return {
                teams: this.allocations
            }
        },
        methods: {
            startDraw() {
                axios.post('/api/tournament/' + this.tournament.id + '/round');
            }
        }
    }
</script>
