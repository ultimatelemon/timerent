
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

export const store = new Vuex.Store({
    state: {
        title: null,
        show_prices: false,
        user: null,
        token: null,
        venue: null,
    },
    mutations: {
        setTitle(state, value) {
            document.title = value ? value + ' — Timerent' : 'Timerent';
            state.title = value;
        },
        setShowPrices(state, value) {
            localStorage.setItem('tr_show_prices', value);
            state.show_prices = value;
        },
        setUser(state, value) {
            console.log('Mutation setUser called with value:', value);
            state.user = value;
            console.log('Mutation setUser called with value:', value);
        },
        setToken(state, value) {
            state.token = value;
        },
        setVenue(state, value) {
            state.venue = value;
        }
    },
    plugins: [createPersistedState()]
});