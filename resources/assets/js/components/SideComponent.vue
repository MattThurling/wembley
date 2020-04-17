<template>
  <div>
    <p class="small mb-0">
      <template v-for="star in restingStars">
        <span :class=" {clickball: auth }" @click="playStar(star.id)">⚽</span>
      </template>
    </p>
    <p class="small mb-0">{{ userName }}</p>
    <h4 class="mb-0">{{ teamName }}</h4>
    <table class="table table-sm">
      <thead>
        <tr :class="getClass(division)">
          <th class="p-0 text-center">D{{division}}</th>
          <th class="py-0 gate text-right">£{{gate}}</th>
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
      restingStars: function() {
        if (this.player) {
          return this.player.stars.filter((star) => {
            return star.pivot.play == 0;
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
      playStar(id) {
        if (this.auth) {
          console.log(id);
          axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/play-star', {'star_id': id}).
          then(response => console.log(response.data));
        }
      },
    }
  };
</script>