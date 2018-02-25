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
    actionFunction({ state, commit, dispatch}) {
        commit('addLoading', 'action-event', { root: true });

        var url = 'url';

        axios.get(url)
            .then(response => {
                // Set data
                commit('removeLoading', 'action-event', { root: true });
                dispatch('finishAjaxCall', {
                    loader: 'action-event',
                    model: 'template',
                    response: response
                }, { root: true });
            })
            .catch(errors => {
                commit('removeLoading', 'action-event', { root: true });
                dispatch('finishAjaxCall', {
                    loader: 'action-event',
                    model: 'template',
                    response: errors,
                }, { root: true });
            });
    }
}

// Mutations
const mutations = {
    mutatorFunction(state) {
        // Do something...
    }
}

export default {
    namespaced,
    state,
    getters,
    actions,
    mutations
}
