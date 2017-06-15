import Vue from 'vue';
import Vuex from 'vuex';
import chat from './modules/chat';
import getters from './getters';

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        chat,
    },
    getters
});

export default store
