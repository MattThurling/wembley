<template>
  <div>
    <p class="mb-0">
        {{ game.round.name }} | Match {{ game.round.position }} of {{ game.round.number_of_matches }}
        
    </p>
    <p class="text-sm">
      Win <strong>£{{ winGate }}</strong> | Lose <strong>£{{ loseGate }}</strong>
    </p>
    <hr />
  </div>
</template>

<script>
    export default {
        props: ['game', 'winGate', 'loseGate']
    }
</script>
