
<template>

  <div class="row no-gutters">

      <div class="col-sm-8">
          <div class="row">
              <side-component
                :teamName="game.home_team.nickname"
                :userName="game.home_user.name"/>
              <div class="col-4">
                  <button
                      @click="sellHandler(game.home_team.id)"
                      class="btn btn-outline-primary btn-sm">
                      Sell
                  </button>
              </div>
          </div>

          <div class="row">
              <side-component
                :teamName="game.away_team.nickname"
                :userName="game.away_user.name"/>
              <div class="col-4">
                  <button
                      @click="sellHandler(game.away_team.id)"
                      class="btn btn-outline-primary btn-sm">
                      Sell
                  </button>
              </div>
          </div>

      </div>

      <div class="col-sm-4 text-center mt-3 mb-3">
          <p class="dealer-status">Waiting for player...</p>
      </div>

  </div>


</template>

<script>
    export default {
        props: ['game', 'sellHandler']
    }

</script>
