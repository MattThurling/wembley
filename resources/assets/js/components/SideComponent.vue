<template>
  <div class="col-8">
    <h4 class="mb-0">{{ teamName }}</h4>
    <p class="small">{{ userName }}</p>
  </div>
</template>

<script>
  export default {
    props: ['teamName', 'userName']
  }
</script>