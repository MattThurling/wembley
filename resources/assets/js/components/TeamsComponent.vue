<template>
    <div class="card mt-3">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-text">Your teams</h5>
            </div>
        </div>
        <div class="card-body">
            <div v-for="allocation in allocations" class="card-text teams">

                <p v-if="allocation.status == 1" class="text-success">
                    {{ allocation.team.name }}
                </p>

                <p v-if="allocation.status == -1" class="text-danger">
                    {{ allocation.team.name }}
                </p>

                <p v-if="allocation.status == 0">
                    {{ allocation.team.name }}
                </p>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['allocations']
    }
</script>
