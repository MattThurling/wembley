<template>
    <div class="col-lg-4">
        <div class="card mt-3">
            <div class="card-header">
                <p class="text-center">Bank balance:</p>
            </div>
            <div class="card-body">
                <div class="card-text">
                    <h1 class="text-center">£{{ balance }}</h1>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['balance'],
    }
</script>
