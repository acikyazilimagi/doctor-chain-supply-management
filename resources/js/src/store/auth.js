import axios from 'axios'
import router from '@/router.js'

export default {
    namespaced: true,
    state:{
        authenticated: false,
        user: null,
    },
    getters:{
        getAuthenticated(state) {
            return state.authenticated
        },
        getUser(state) {
            return state.user
        },
    },
    mutations:{
        SET_AUTHENTICATED (state, value) {
            state.authenticated = value
        },
        SET_USER (state, value) {
            state.user = value
        },
    },
    actions:{
        async checkAuth({commit,dispatch}){
            return axios
                .get('/api/user')
                .then((response) => {
                    return true
                }).catch((e) => {
                    return false
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
            }).catch((e)=>{
                commit('SET_USER', null)
                commit('SET_AUTHENTICATED',false)
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
