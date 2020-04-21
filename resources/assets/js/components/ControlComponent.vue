<template>
  <div>
    <button
        v-if="xGame.owner"
        @click="handler"
        class="btn btn-primary"
        v-intro="'As tournament creator, you are responsible for starting matches and ending auctions. (Temporary for this prototype - everything will be on timers eventually!)'">
        {{ context.buttonText }}
    </button>
    <span
      class="small mr-2"
      v-else
      v-intro="'The creator of the tournament controls the start of matches and the end of auctions. Hold tight! This is a temporary solution and everything will eventually be on timers. In the meantime, you can keep the game moving by letting them know whether you want to buy stars or teams in the chat.'">
      {{ context.punterText }}</span>
    <button
        class="btn btn-outline-info help-button"
        @click="$intro().start()">
        ?
    </button>
  </div>
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
        let punterText = 'Waiting for dealer';
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