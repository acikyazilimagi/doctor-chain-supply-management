import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import global from '@/src/store/global.js'
import auth from '@/src/store/auth.js'
const store = createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth,
        global
    }
})
export default store
