<template>
    <div>
        <div class="row no-gutters">
            <div class="col-md-6 text-center mt-3 mb-3">
                <button @click="startRoundHandler" class="btn btn-success btn-lg">Start</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['startRoundHandler']
    }
</script>
