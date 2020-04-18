<template>
    <form v-on:submit.prevent="submitBid">    
        <div class="input-group input-group-sm mb-3 mr-2">
            
                <input type="text" class="form-control" v-model="amount">
                <div class="input-group-append mr-3">
                    <button
                        class="btn btn-outline-primary"
                        type="submit">
                        Bid
                    </button>
                </div>
        </div>
    </form> 
</template>


<script>
    export default {
        props: ['bidHandler'],
        data() {
            return {
                amount: null
            }
        },
        methods: {
            submitBid() {
                axios.post('/api/tournament/' + this.$store.getters.GET_TOURNAMENT_ID + '/bid',
                    {
                        amount: this.amount,
                        side: this.$store.getters.GET_GAME.bid_side,
                    })
                    .then(response => {
                        console.log(response);
                    })
                    .catch(err => {
                        alert('Invalid bid');
                    });
                this.amount = null;
            }
        }
    };
</script>
