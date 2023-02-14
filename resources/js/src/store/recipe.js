import axios from 'axios'

export default {
    namespaced: true,
    state:{
        allRecipes: [],
        myRecipes: [],
    },
    getters:{
        getAllRecipes(state) {
            return state.allRecipes
        },
        getMyRecipes(state) {
            return state.myRecipes
        },
    },
    mutations:{
        SET_ALL_RECIPES(state, payload) {
            state.allRecipes = payload
        },
        SET_MY_RECIPES(state, payload) {
            state.myRecipes = payload
        }
    },
    actions:{
        async fetchAllRecipes({ commit }) {
            await axios.get('/api/recipes/all')
                .then((response) => {
                    commit('SET_ALL_RECIPES', response.data.data)
                })
        },

        async fetchMyRecipes({ commit }) {
            await axios.get('/api/account/recipes/my')
                .then((response) => {
                    commit('SET_MY_RECIPES', response.data.data)
                })
        }
    }
}
