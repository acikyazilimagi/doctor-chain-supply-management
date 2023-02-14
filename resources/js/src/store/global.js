import axios from 'axios'

export default {
    namespaced: true,
    state:{
        categories:[],
        specialtiesCategories:[],
    },
    getters:{
        getCategories(state) {
            return state.categories
        },
        getSpecialtiesCategories(state) {
            return state.specialtiesCategories
        }
    },
    mutations:{
        SET_CATEGORIES (state, value) {
            state.categories = value
        },
        SET_SPECIALTIES_CATEGORIES (state, value) {
            state.specialtiesCategories = value
        }
    },
    actions:{
        async getRecipeItemCategories({commit}){
            await axios.get('/api/recipes/items/categories')
                .then((response) => {
                    commit('SET_CATEGORIES', response.data.data)
                })
        },
        async getSpecialtiesCategories({commit}){
            await axios.get('/api/specialties')
                .then((response) => {
                    commit('SET_SPECIALTIES_CATEGORIES', response.data.data)
                })
        },
    }
}
