<template>
    <div class="row no-gutters">

        <div class="col-sm-8">
            <div class="row">
                <div class="col-4">
                    <transition
                        name="side"
                        enter-active-class="animated bounceIn">
                        <side-component
                        :teamName="xGame.home_team.name"
                        :userName="xGame.home_player ? xGame.home_player.user.name : 'FOR SALE'"
                        :player="xGame.home_player"
                        :division="xGame.home_team.division.level"
                        :gate="numberWithCommas(xGame.home_team.gate)"
                        :key="xGame.home_team.id"/>
                    </transition>
                </div>

                <div class="col-4">
                    
                    <svg viewBox="0 0 130 55" xmlns="http://www.w3.org/2000/svg">

                        <template v-for="(odd, i) in xGame.home_team.division.odds">
                            <text x="7" :y="53-8*i" class="goals">{{ i }}</text>
                            <line
                                x1="20"
                                :y1="50-8*i"
                                :x2="strokeEnd(odd.home_odds)"
                                :y2="50-8*i"    
                                :style="getStyle(xGame.home_team.division.level)" />
                        </template>


                    </svg>
                </div>
                
                <div class="col-4">
                    <bid-component v-if="xGame.phase == 'bid' && xGame.bid_side == 'home'" />
                    <h1 v-if="xGame.phase == 'match'">{{ xGame.match.home_score }}</h1>
                    <redraw-component v-if="xGame.phase == 'redraw'" side="away" />
                    <sell-component v-if="xGame.phase == 'sell'" :id="xGame.home_team.id" />
                    <boost-component
                        v-if="xGame.phase == 'draw'"
                        :player="xGame.home_player" />
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <transition
                        name="side"
                        enter-active-class="animated bounceIn">
                        <side-component
                        :teamName="xGame.away_team.name"
                        :userName="xGame.away_player ? xGame.away_player.user.name : 'FOR SALE'"
                        :player="xGame.away_player"
                        :division="xGame.away_team.division.level"
                        :gate="numberWithCommas(xGame.away_team.gate)"
                        :key="xGame.away_team.id"/>
                    </transition>
                </div>


                <div class="col-4">

                    <svg viewBox="0 0 130 55" xmlns="http://www.w3.org/2000/svg">
                        
                        <template v-for="(odd, i) in xGame.away_team.division.odds">
                            <text x="7" :y="53-8*i" class="goals">{{ i }}</text>
                            <line
                                x1="20"
                                :y1="50-8*i"
                                :x2="strokeEnd(odd.away_odds)"
                                :y2="50-8*i"
                                :style="getStyle(xGame.away_team.division.level)" />
                        </template>
                        
                    </svg>

                </div>

                <div class="col-4">
                    <bid-component v-if="xGame.phase == 'bid' && xGame.bid_side == 'away'"/>
                    <h1 v-if="xGame.phase == 'match'">{{ xGame.match.away_score }}</h1>
                    <redraw-component v-if="xGame.phase == 'redraw'" side="home" />
                    <sell-component v-if="xGame.phase == 'sell'" :id="xGame.away_team.id" />
                    <boost-component
                        v-if="xGame.phase == 'draw'"
                        :player="xGame.away_player"/>
                </div>

            </div>

        </div>

        <div class="col-sm-4">
            <transition 
                name="auction"
                enter-active-class="animated zoomIn"
                leave-active-class="animated zoomOut">
                <auction-component v-if="xGame.phase == 'bid'" key="1"/>
            </transition>
        </div>

        

    </div> 
</template>

<script>
    import ControlComponent from './ControlComponent.vue';
    import BidComponent from './BidComponent.vue';
    import AuctionComponent from './AuctionComponent.vue';
    import SellComponent from './SellComponent.vue';
    export default {
        components: {
            ControlComponent,
            BidComponent,
            AuctionComponent,
            SellComponent,
        },
        data() {
            return {
                // For testing animations
                count: 0
            }
        },
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
            },
            triggerCount() {
                this.count++
            }   
        },
        computed: {
            xGame: function() {
                return this.$store.getters.GET_GAME;
            }, 
        }
    };

</script>

<style>
    .goals {
        fill: white;
        font-size: 8px;
    }
</style>
