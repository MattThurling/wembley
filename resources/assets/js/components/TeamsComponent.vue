<template>
    <div class="col-sm-8">
        <table class="table table-sm mt-3">
            <thead>
                <tr>
                    <th scope="col">Your teams</th>
                    <th class="text-center" scope="col">Division</th>
                    <th class="text-right" scope="col">Gate</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="allocation in allocations" :class=getStatusClass(allocation)>
                    <td>{{ allocation.team.name }}</td>
                    <td class="text-center" v-if="allocation.team.division">{{ allocation.team.division.name }}</td>
                    <td class="text-right">{{ numberWithCommas(allocation.team.gate) }}</td>
                </tr>
            </tbody>
        </table>
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
