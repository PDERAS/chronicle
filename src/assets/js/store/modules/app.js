// State
const state = {
    errors: {
        products: []
    },
    loading: [],
    toast: null
}

// Getters
const getters = {
    errors ()   { return state.errors;  },
    loading ()  { return state.loading; },
    toast ()    { return state.toast;   }
}

// Actions
const actions = {
    finishAjaxCall({ state, commit }, { response, model, loader }) {
        commit('setErrors', { errors: 'reset' });
        commit('removeLoading', loader);

        if (typeof response.response == 'undefined') {
            if (response.data && response.data.message) {
                commit('setToast', response.data.message);
            }
        } else {
            var _response = response.response;
            if (typeof _response.data.session !== 'undefined') {
                commit('setToast', _response.data.session[0]);
            } else {
                commit('setErrors', { model: model, errors: _response.data });
            }
        }
    }
}

// Mutations
const mutations = {
    addLoading(state, loading) {
        state.loading.push(loading);
    },

    setErrors(state, { model, errors }) {
        if (model) {
            if (state.errors[model]) {
                Object.assign(state.errors[model], errors);
            } else {
                state.errors[model] = errors;
            }
        } else {
            if (errors === 'reset') {
                state.errors = {
                    products: []
                };
            } else {
                state.errors = errors;
            }
        }
    },

    clearErrors(state, model) {
      state.errors[model] = [];
    },

    removeLoading(state, loading) {
        state.loading = state.loading.filter(l => {
            return l !== loading;
        });
    },
}

export default {
    state,
    getters,
    actions,
    mutations
}


