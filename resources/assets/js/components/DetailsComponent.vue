<template>
  <div>
    <transition
      name="details-appear"
      enter-active-class="animated bounceIn">
      <p
        class="mb-0 small"
        v-intro="'The progress of the tournament. The aim is to get teams through each round, earn money, and then have one of your teams win the final!'">
          {{ $store.getters.GET_GAME.round.name }} | Match {{ $store.getters.GET_GAME.round.position }} of {{ $store.getters.GET_GAME.round.number_of_matches }}
          
      </p>
    </transition>
    <p
      class="small"
      v-intro="'You earn money whenever one of your team plays. The owner of the winning team gets 2/3rds of the HOME (top) team\'s gate money and goes through to the next round. The loser gets 1/3rd and the team goes out. In this prototype, in the case of a draw the away team automatically wins and goes through. Replays coming soon!'"> 
      Win <strong>₿{{ numberWithCommas(2*$store.getters.GET_GAME.home_team.gate/3) }}</strong> | Lose <strong>₿{{ numberWithCommas($store.getters.GET_GAME.home_team.gate/3) }}</strong>
    </p>
    <hr />
  </div>
</template>

