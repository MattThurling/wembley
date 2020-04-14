<template>

    <button
        @click="handler"
        class="btn btn-outline-primary btn-sm">
        Play
    </button>

</template>

<script>
    export default {
        props: ['side'],
        methods: {
            handler() {
                axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/redraw', {side: this.side}).then(response => {
                    console.log(response);
                });
            }
        }
    };

</script>
