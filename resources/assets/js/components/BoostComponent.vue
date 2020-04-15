<template>
  <div>
    <div class="input-group" v-if="auth">
      <select class="custom-select custom-select-sm" v-model="selected">
        
        <option value="0" selected>Boost...</option>
        <option
          v-for="(star, index) in $store.getters.GET_GAME.stars"
          :value="star.id"
          :disabled="getDisabled(star.id)">
          {{ star.type }} (£{{ numberWithCommas(star.price) }})   
        </option>
      </select>
      <div class="input-group-append">
        <button @click="buyStar" class="btn btn-outline-primary btn-sm" type="button">Buy</button>
      </div>

    </div>
    <p class="mt-2">
      <template v-for="star in activeStars">
        <span :class="{ clickball: auth }" @click="restStar(star.id)">⚽</span>
      </template>
    </p>
  </div>

</template>

<script>
  export default {
    props : ['player'],
    data() {
        return {selected: 0}
    },
    computed: {
      activeStars: function() {
        if (this.player) {
          return this.player.stars.filter((star) => {
            return star.pivot.play == 1;
          });
        }
      },
      auth: function() {
        if (this.$store.getters.GET_GAME.player.id == this.player.id) return true;
      }
    },
    methods: {
      buyStar() {
       axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/buy-star', {'star_id': this.selected}).
        then(response => console.log(response.data));
      },
      restStar(id) {
        if (this.auth) {
          axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/rest-star', {'star_id': id}).
          then(response => console.log(response.data));
        }
      },
      getDisabled(id) {
        if (this.player.stars.filter(star => star.id == id).length) return true
      },
    }
  };
</script>