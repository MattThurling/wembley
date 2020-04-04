<template>
    <p>
        {{ tournament.round.name }} | Match {{ tournament.round.position }} of {{ tournament.round.number_of_matches }}
        | Gate: Win <span class="text-success">£{{ winGate }}</span> Lose
        <span class="text-danger">£{{ loseGate }}</span>
    </p>
</template>

<script>
    export default {
        props: ['tournament', 'winGate', 'loseGate']
    }
</script>
