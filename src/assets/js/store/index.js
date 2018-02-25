import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

/* Modules */
import notes from "./modules/notes"

export default new Vuex.Store({
    modules: {
        notes,
        sections
    },
});

