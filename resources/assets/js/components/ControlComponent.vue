<template>
  <div class="col-sm-4 text-center mb-3">
    <template v-if="xGame.owner">
      <button
          @click="handler" class="btn btn-primary btn-lg">
          {{ buttonAction }}
      </button>
    </template>
  </div>
</template>

<script>
  export default {
    computed: {
      xGame: function() {
          return this.$store.getters.GET_GAME;
      },
      buttonAction: function() {
        let verb = 'match'; // default for draw, redraw and bid phases
        if (this.xGame.phase == 'round') verb = '';
        if (this.xGame.phase == 'match') verb = 'next';
        return verb;
      }
    },
    methods: {
      handler() {
        this.apiPost('tournament/' + this.xGame.tournament.id + '/' + this.buttonAction, {});
      }
    }
  };
</script>