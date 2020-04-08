<template>
    <div class="col-sm-8 mt-3">
        <h6 class="text-center">YOUR TEAMS</h6>
        <div class="card-deck">
            <div
                v-for="allocation in allocations"
                class="card card-team mb-2">
                <div class="card-header text-center m-0 py-0">
                    <p class="text-small p-0 m-0">{{ allocation.team.nickname }}</p>
                </div>
<!--                <div class="card-body text-center" v-if="allocation.team.division">{{ allocation.team.division.name }}</div>-->
                <div
                    class="card-body card-body-team text-center">
                    £{{ numberWithCommas(allocation.team.gate) }}
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
                let rowClass = "";
                if (allocation.status == 1) rowClass = "table-success";
                if (allocation.status == -1) rowClass = "table-danger";
                return rowClass;
            }
        }
    }
</script>
