<template>
    <div
        class="col-sm-8 col-md-4 mt-3">
        <h6 class="text-center">BANK BALANCE</h6>
        <div class="card">

            <div
                class="card-body text-center align-bottom bank"
                v-intro="'This is your bank balance, shown in bitcoin to futureproof against the imminent obsolescence of government-issued currencies.'">           
                <img src="/images/opengraph.png" width="30px" class="mx-1 btc"/>
                <span>{{ numberWithCommas($store.getters.GET_GAME.player.balance) }}</span>
            </div>
        </div>

        <h6 class="text-center mt-3">STARS</h6>
        <div
            class="mt-3"
            v-intro="'You can buy star players and boost your score by 1 goal per star. To play or remove a star, click on the ball. (You don\'t have any stars at the beginning of the game and it\'s probably best to wait until the later rounds when you have fewer teams and more money!)'">
            <div v-if="restingStars" v-for="star in restingStars">
                <hr />
                <div class="row">
                    <div class="col-2 text-center">
                        <h2 class="my-auto clickball" @click="playStar(star.id)">
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
            let stars = this.$store.getters.GET_GAME.player.stars;

            if (stars) return stars.filter((star) => {
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

