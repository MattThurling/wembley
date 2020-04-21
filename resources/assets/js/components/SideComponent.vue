<template>
  <div>
    <p class="small mb-0">
      <template v-for="star in activeStars">
        <span :class=" {clickball: auth }" @click="restStar(star.id)">⚽</span>
      </template>
    </p>
    <p class="small mb-0">{{ userName }}</p>
    <h4 class="mb-0">{{ teamName }}</h4>
    <table class="table table-sm">
      <thead>
        <tr :class="getClass(division)">
          <th class="pl-1 py-0 text-left">D{{division}}</th>
          <th class="py-0 gate text-right">₿{{gate}}</th>
        </tr>
      </thead>
    </table>
  </div>     
</template>

<script>
  export default {
    props: ['player', 'teamName', 'userName', 'division', 'gate'],
    data() {
      return {
        count: 0
      }
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
      getClass(division) {
        let myClass = "";
        if (division == 1) myClass="top-flight";
        if (division == 2) myClass="mid-tier";
        if (division == 3) myClass="lower-league";
        return myClass;
      },
      restStar(id) {
        if (this.auth) {
          console.log(id);
          axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/rest-star', {'star_id': id}).
          then(response => console.log(response.data));
        }
      },
    }
  };
</script>