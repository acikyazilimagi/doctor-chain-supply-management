import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import global from '@/src/store/global.js'
const store = createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        global
    }
})
export default store
