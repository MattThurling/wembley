<template>
    <div class="col-sm-8 mt-3">
        <h6 class="text-center">YOUR TEAMS</h6>
        <!-- <div class="row">
            <div class="col-4 text-center top-flight">
                <span class="key-top">•</span> <span class="text-sm">Top Flight</span>
            </div>
            <div class="col-4 text-center mid-tier">
                <span class="key-mid">•</span> <span class="text-sm">Mid Tier</span>
            </div>
            <div class="col-4 text-center lower-league">
                <span class="key-lower">•</span> <span class="text-sm">Lower</span>
            </div>
        </div> -->
        <div class="row">
            <div v-for="allocation in allocations" class="col-6 col-md-3">
                <div class="card card-team mb-2">
                    <div class="card-header text-center m-0 p-0" :class="getStatusClass(allocation)">
                        <p class="text-small p-0 m-0">{{ allocation.team.nickname }}</p>
                    </div>

                    <div
                        class="card-body card-body-team text-center">
                        £{{ numberWithCommas(allocation.team.gate) }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props:['allocations','conversionHandler'],
        methods: {
            // TODO: DRY this up by defining it elsewhere
            numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            getStatusClass(allocation) {
                let cardClass = "";
                if (allocation.team.division.level == 1) cardClass = "top-flight";
                if (allocation.team.division.level == 2) cardClass = "mid-tier";
                if (allocation.team.division.level == 3) cardClass = "lower-league";
                return cardClass;
            }
        }
    }
</script>
