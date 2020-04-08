<template>
    <div class="col-lg-4 mt-3">
        <h6 class="text-center">BANK BALANCE</h6>
        <div class="card">
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
