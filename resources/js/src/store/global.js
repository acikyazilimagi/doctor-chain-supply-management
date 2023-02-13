import axios from 'axios'
import router from '@/router.js'

export default {
    namespaced: true,
    state:{
        authenticaded:false,
        user: null,
        categories:[],
        specialtiesCategories:[],
    },
    getters:{
        getCategories(state) {
            return state.categories
        },
        getAuthenticaded(state) {
            return state.authenticaded
        },
        getUser(state) {
            return state.user
        },
        getSpecialtiesCategories(state) {
            return state.specialtiesCategories
        }
    },
    mutations:{
        SET_AUTHENTICATED (state, value) {
            state.authenticated = value
        },
        SET_USER (state, value) {
            state.user = value
        },
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

        async setUser({commit}){
            return axios.get('/api/user').then(({data})=>{
                commit('SET_USER',data)
                commit('SET_AUTHENTICATED',true)
            })
        },
        async loginUser({commit}){
            return axios.get('/api/user').then(({data})=>{
                commit('SET_USER',data)
                commit('SET_AUTHENTICATED',true)
                router.push({name:'Index'})
            })
        },
        async logoutUser({commit}){
            await axios.post('/api/auth/logout').then(({data})=>{
                commit('SET_USER', null)
                commit('SET_AUTHENTICATED',false)
                router.push({name:"Auth.Login"})
            })

        }
    }
}
