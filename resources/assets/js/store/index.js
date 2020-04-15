export default {

  state: {

    tournament_id: '',
    game: {
        home_team: {gate: 0, division: {}},
        away_team: {gate: 0, division: {}},
        player: {balance:0},
        high_bid_amount: 0,
        home_player: {user: {}, stars: []},
        away_player: {user: {}, stars: []},
        round: {},
        owner: false,
        bid: {
            high_bid: {
                player: {
                    user: {}
                }
            }
        }
    },
    loading: false

  },

  getters: {

    GET_GAME(state){ //take parameter state

      return state.game;

    },
    GET_LOADING(state){

      return state.loading;
    },
    GET_TOURNAMENT_ID(state){

      return state.tournament_id;
    },
  },

  actions: {

    SET_TOURNAMENT_ID(context, id) {
      context.commit("TOURNAMENT_ID", id);
    },

    UPDATE_TOURNAMENT(context, id){

      axios.get("/api/tournament/" + id)
      .then((response)=>{

      context.commit("GAME",response.data);

      })
      .catch(()=>{

      console.log("Error........")

      });
    }
  },

  mutations: {
    GAME(state,data) {
      return state.game = data;
    },
    LOADING(state,position) {
      return state.loading = position;
    },
    TOURNAMENT_ID(state,id) {
      return state.tournament_id = id;
    }
  }
}