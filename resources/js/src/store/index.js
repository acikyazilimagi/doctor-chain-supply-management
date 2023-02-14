import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import global from '@/src/store/global.js'
import auth from '@/src/store/auth.js'
import recipe from '@/src/store/recipe.js'
const store = createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth,
        recipe,
        global
    }
})
export default store
