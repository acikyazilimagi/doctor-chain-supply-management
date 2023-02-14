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
            if(localStorage.getItem('token') && localStorage.getItem('token') != 'undefined'){
                commit('SET_AUTHENTICATED',true)
                await dispatch('setUser')
            }else{
                localStorage.removeItem('token')
                dispatch('logoutUser')
            }
        },

        async setUser({commit}){
            return axios.get('/api/user').then(({data})=>{
                commit('SET_USER',data)
                commit('SET_AUTHENTICATED',true)
            })
        },

        async loginUser({commit,state,dispatch}, payload){
            state.authenticated = false
            await axios.post('/api/auth/login', payload).then((response) => {
                console.log(response)
                if(response.status === 200){
                    let token = response.data.access_token
                    localStorage.setItem('token', token)
                    dispatch('setUser')
                    commit('SET_AUTHENTICATED',true)
                    router.push({name:'Index'})
                }
            }).catch((e)=>{
                if(e.response.status === 422){
                    console.log(e.response.data.message)
                }
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
