<template>
  <div>
    <transition
      name="details-appear"
      enter-active-class="animated bounceIn">
      <p class="mb-0 small">
          {{ $store.getters.GET_GAME.round.name }} | Match {{ $store.getters.GET_GAME.round.position }} of {{ $store.getters.GET_GAME.round.number_of_matches }}
          
      </p>
    </transition>
    <p class="small">
      Win <strong>£{{ numberWithCommas(2*$store.getters.GET_GAME.home_team.gate/3) }}</strong> | Lose <strong>£{{ numberWithCommas($store.getters.GET_GAME.home_team.gate/3) }}</strong>
    </p>
    <hr />
  </div>
</template>

