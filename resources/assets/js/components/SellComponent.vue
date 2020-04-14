<template>

  <button
      @click="handler"
      class="btn btn-outline-primary btn-sm">
      Sell
  </button>

</template>

<script>
    export default {
        props: ['id'],
        methods: {
          handler() {
            axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/sell', {team_id: this.id}).then(response => {
                    console.log(response);
            });
          }
        }
    };

</script>
