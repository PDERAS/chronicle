const namespaced = true

// State
const state = {
    all: [],
    active: {}
}

// Getters
const getters = {
    all:    state => state.all,
    active: state => state.active,
}

// Actions
const actions = {
    get({ state, commit, dispatch}, id) {
        var loader = 'get-section';
        commit('addLoading', loader, { root: true });

        var url = '/sections/' + id;

        axios.get(url)
            .then(response => {
                commit('setActive', response.section);
                dispatch('finishAjaxCall', {
                    loader: loader,
                    model: 'section',
                    response: response
                }, { root: true });
            })
            .catch(errors => {
                dispatch('finishAjaxCall', {
                    loader: loader,
                    model: 'section',
                    response: errors,
                }, { root: true });
            });
    }
}

// Mutations
const mutations = {
    setActive(state, section) {
        state.active = section;
    },

    resetActive(state) {
        state.active = {};
    }
}

export default {
    namespaced,
    state,
    getters,
    actions,
    mutations
}
