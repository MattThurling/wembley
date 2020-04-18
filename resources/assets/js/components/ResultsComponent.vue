<template>
  <div>
    <hr>

    <h6 class="text-center">RESULTS</h6>
    <div v-for="stage in $store.getters.GET_GAME.results">
      <p class="text-center">{{ stage.name }}</p>
      <table class="table table-sm results-table">
        <tbody>
          <tr class="results-row" v-for="rubber in stage.rubbers">
            <td style="width: 41%">{{ rubber.home_team_name }}</td>
            <td style="width: 10%">{{ rubber.home_score }}</td>
            <td style="width: 10%">{{ rubber.away_score }}</td>
            <td style="width: 39%">{{ rubber.away_team_name }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

