<template>
    <div class="col-sm-8 mt-3">
        <h6 class="text-center">YOUR TEAMS</h6>

        <transition-group
            name="teams"
            enter-active-class="animated flipInX"
            leave-active-class="animated flipOutX"
            move-class="animated slideInUp"
            tag="div"
            class="row">
            <div
                v-for="allocation in $store.getters.GET_GAME.allocations"
                :key="allocation.id"
                class="col-6 col-md-3">
                <div class="card card-team mb-2">
                    <div class="card-header text-center m-0 p-0" :class="getStatusClass(allocation)">
                        <p class="text-small p-0 m-0">{{ allocation.team.name }}</p>
                    </div>
                    <div
                        class="card-body card-body-team text-center">
                        £{{ numberWithCommas(allocation.team.gate) }}
                    </div>
                </div>
            </div>
        </transition-group>


    </div>
</template>

<script>
    export default {
        methods: {
            getStatusClass(allocation) {
                let cardClass = "";
                if (allocation.team.division.level == 1) cardClass = "top-flight";
                if (allocation.team.division.level == 2) cardClass = "mid-tier";
                if (allocation.team.division.level == 3) cardClass = "lower-league";
                return cardClass;
            }
        }
    };
</script>
