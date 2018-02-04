import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

/* Modules */
import template from "./modules/template"

export default new Vuex.Store({
    modules: {
        template
    },
});

