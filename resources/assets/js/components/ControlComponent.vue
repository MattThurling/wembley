<template>
  <button
      v-if="xGame.owner"
      @click="handler"
      class="btn btn-primary">
      {{ context.buttonText }}
  </button>
  <p v-else>{{ context.punterText }}</p>
</template>

<script>
  export default {
    computed: {
      xGame: function() {
          return this.$store.getters.GET_GAME;
      },
      context: function() {
        let verb = 'match';
        let buttonText = 'Play';
        let punterText = '';
        if (this.xGame.phase == 'round') {
          verb = '';
          buttonText = 'Start';
          punterText = 'Waiting for dealer';
        }
        if (this.xGame.phase == 'match') {
          verb = 'next';
          buttonText = 'Next';
          punterText = 'Waiting for dealer';
        }
        if (this.xGame.phase == 'bid') {
          verb = 'close-auction';
          buttonText = 'Close auction';
          punterText = 'Waiting for dealer';
        }
        return {verb, buttonText, punterText};
      }
    },
    methods: {
      handler() {
        this.apiPost('tournament/' + this.xGame.tournament.id + '/' + this.context.verb, {});
      }
    }
  };
</script>