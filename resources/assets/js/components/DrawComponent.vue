<template>
    <div class="row no-gutters">

        <div class="col-sm-8">
            <div class="row">
                <side-component
                :teamName="game.home_team.nickname"
                :userName="game.home_user.name"
                :division="game.home_team.division.level"
                :gate="homeGate"/>

                <div class="col-4">
                    
                    <svg viewBox="0 0 130 45" xmlns="http://www.w3.org/2000/svg">

                        <template v-for="(odd, i) in homeOdds">
                            <text x="7" :y="41-9*i" class="goals">{{ i }}</text>
                            <line
                                x1="20"
                                :y1="39-9*i"
                                :x2="strokeEnd(odd.away_odds)"
                                :y2="39-9*i"
                                :style="getStyle(game.home_team.division.level)" />
                        </template>


                    </svg>
 
                </div>

                <div class="col-4">
                    <div class="input-group">
                        <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                            <option selected>Boost...</option>
                            <option value="1">Goalkeeper (£2,500,000)</option>
                            <option value="2">Defender (£5,000,000)</option>
                            <option value="3">Three</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary btn-sm" type="button">Buy</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <side-component
                :teamName="game.away_team.nickname"
                :userName="game.away_user.name"
                :division="game.away_team.division.level"
                :gate="awayGate"/>


                <div class="col-4">

                    <svg viewBox="0 0 130 45" xmlns="http://www.w3.org/2000/svg">
                        
                        <template v-for="(odd, i) in awayOdds">
                            <text x="7" :y="41-9*i" class="goals">{{ i }}</text>
                            <line
                                x1="20"
                                :y1="39-9*i"
                                :x2="strokeEnd(odd.away_odds)"
                                :y2="39-9*i"
                                :style="getStyle(game.away_team.division.level)" />
                        </template>
                        
                        

                    </svg>

                </div>

                <div class="col-4">
                    <div class="input-group">
                        <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                            <option selected>Boost...</option>
                            <option value="1">Goalkeeper (£2,500,000)</option>
                            <option value="2">Defender (£5,000,000)</option>
                            <option value="3">Three</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary btn-sm" type="button">Buy</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-sm-4 text-center mt-3 mb-3">
            <button
                v-if="owner"
                @click="playMatchHandler" class="btn btn-primary btn-lg">
                Play
            </button>
            <p v-else class="dealer-status">Waiting for dealer...</p>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['game', 'playMatchHandler', 'owner', 'homeGate', 'awayGate'],
        methods: {
            strokeEnd(x) {

                return 20 + x * 0.18;
            },
            getStyle(division) {
                let style = "stroke-width:1.5;stroke:";
                if (division == 1) style += "#ed000088;";
                if (division == 2) style += "#1414e044;";
                if (division == 3) style += "#37ed0088;";
                return style;
            }      
        },
        computed: {
            homeOdds: function() {
                return this.game.home_team.division.odds;
            },
            awayOdds: function() {
                return this.game.away_team.division.odds;
            }
        }
    };

</script>

<style>
    .goals {
        fill: white;
        font-size: 8px;
    }
</style>
