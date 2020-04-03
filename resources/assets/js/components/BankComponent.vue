<template>
    <div class="col-lg-4">
        <div class="card mt-3">
            <div class="card-header">
                <p class="text-center">Bank balance:</p>
            </div>
            <div class="card-body">
                <div class="card-text">
                    <h1 class="text-center" v-if="player">£{{ numberWithCommas(player.balance * 120) }}</h1>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['player', 'conversionHandler'],
        methods: {
            // TODO: DRY this up by defining it elsewhere
            numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        }
    }
</script>
