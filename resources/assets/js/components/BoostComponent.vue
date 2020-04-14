<template>
  
  <div class="input-group">
    <select class="custom-select custom-select-sm" v-model="selected">
      
      <option value="0" selected>Boost...</option>
      <option
        v-for="(star, index) in $store.getters.GET_GAME.stars"
        :value="star.id">{{ star.type }} (£{{ numberWithCommas(star.price) }})
      </option>
    </select>
    <div class="input-group-append">
      <button @click="handler" class="btn btn-outline-primary btn-sm" type="button">Buy</button>
    </div>

  </div>

</template>

<script>
  export default {
    data() {
        return {selected: 0}
    },
    methods: {
      handler() {
       axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/buy-star', {'star_id': this.selected}).
        then(response => console.log(response.data));
      }
    }
  };
</script>