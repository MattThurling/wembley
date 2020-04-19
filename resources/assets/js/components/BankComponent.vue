<template>
    <div class="col-sm-8 col-md-4 mt-3">
        <h6 class="text-center">BANK BALANCE</h6>
        <div class="card">
            <div class="card-body">
                <div class="card-text">
                    <h4 class="text-center text-responsive">£{{ numberWithCommas($store.getters.GET_GAME.player.balance) }}</h4>
                </div>
            </div>
        </div>

        <h6 class="text-center mt-3">STARS</h6>
        <div class="mt-3">
            <div v-for="star in restingStars">
                <hr />
                <div class="row">
                    <div class="col-2 text-center">
                        <h2 class="my-auto" :class="clickball" @click="playStar(star.id)">
                            ⚽
                        </h2>
                    </div>
                    <div class="col-10 my-auto">
                        {{ star.type }} (+ 1 goal)
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
  export default {
    computed: {
        restingStars: function() {
            return this.$store.getters.GET_GAME.player.stars.filter((star) => {
                return star.pivot.play == 0;
            });
        },
    },
    methods: {
        playStar(id) {
            axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/play-star', {'star_id': id}).
            then(response => console.log(response.data));
            },
        }
    };
</script>

