/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//support vuex
import Vuex from 'vuex';
Vue.use(Vuex);
import storeData from "./store/index";

const store = new Vuex.Store(
   storeData
)

import api from "./api/index";
import helpers from "./helpers/index"
Vue.mixin(api);
Vue.mixin(helpers);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('chat-component', require('./components/ChatComponent.vue').default);
Vue.component('match-component', require('./components/MatchComponent.vue').default);
Vue.component('lobby-component', require('./components/LobbyComponent.vue').default);
Vue.component('teams-component', require('./components/TeamsComponent.vue').default);
Vue.component('redraw-component', require('./components/RedrawComponent.vue').default);
Vue.component('round-component', require('./components/RoundComponent.vue').default);
Vue.component('tournament-component', require('./components/TournamentComponent.vue').default);
Vue.component('details-component', require('./components/DetailsComponent.vue').default);
Vue.component('sell-component', require('./components/SellComponent.vue').default);
Vue.component('side-component', require('./components/SideComponent.vue').default);
Vue.component('boost-component', require('./components/BoostComponent.vue').default);
Vue.component('control-component', require('./components/ControlComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)

const app = new Vue({
    el: '#app',
    store,
});
