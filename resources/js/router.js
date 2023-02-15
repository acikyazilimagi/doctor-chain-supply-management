import { createRouter, createWebHistory } from 'vue-router';
import { h, resolveComponent } from 'vue'
import store from '@/src/store/index.js'

const routes = [
    {
        path: "/",
        component: () => import(/* webpackChunkName: "Index" */ '@/src/pages/Index.vue'),
        name: "Index",
        meta: {
            layout: "FullWidth",
        },
    },
    {
        path: '/tum-listeler',
        component: () => import(/* webpackChunkName: "RecipeAll" */ '@/src/pages/Recipe/All.vue'),
        name: 'Recipes.All',
        meta: {
            layout: 'FullWidth'
        },
    },
    {
        path: '/yeni-istek-olustur',
        component: () => import(/* webpackChunkName: "AccountRecipeCreate" */ '@/src/pages/Account/Recipe/Create.vue'),
        name: 'Recipes.Create',
        meta: {
            layout: 'FullWidth',
            middleware: "auth"
        },
    },
    {
        path: '/istek-listem',
        component: () => import(/* webpackChunkName: "AccountRecipeIndex" */ '@/src/pages/Account/Recipe/Index.vue'),
        name: 'Recipes.Index',
        meta: {
            layout: 'FullWidth',
            middleware: "auth"
        },
    },
    {
        component: {
            render() {
                return h(resolveComponent('router-view'))
            },
        },
        children: [
            {
                path: '/auth/login',
                component: () => import(/* webpackChunkName: "AuthLogin" */ '@/src/pages/Auth/Login.vue'),
                name: 'Auth.Login',
                meta: {
                    layout: 'FullWidth',
                    middleware: "guest"
                }
            },
            {
                path: '/auth/register',
                component: () => import(/* webpackChunkName: "AuthRegister" */ '@/src/pages/Auth/Register.vue'),
                name: 'Auth.Register',
                meta: {
                    layout: 'FullWidth',
                    middleware: "guest"
                }
            },
            {
                path: '/hesabim',
                component: () => import(/* webpackChunkName: "AccountProfile" */ '@/src/pages/Account/Profile.vue'),
                name: 'Account.Profile.Index',
                meta: {
                    middleware: "auth"
                }
            },
            {
                path: '/hesabimi-guncelle',
                component: () => import(/* webpackChunkName: "AccountEdit" */ '@/src/pages/Account/Edit.vue'),
                name: 'Account.Profile.Edit',
                meta: {
                    middleware: "auth"
                }
            },
            {
                path: '/parola-degistir',
                component: () => import(/* webpackChunkName: "AccountChangePassword" */ '@/src/pages/Account/ChangePassword.vue'),
                name: 'Account.Profile.ChangePassword',
                meta: {
                    middleware: "auth"
                }
            },
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/',
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.isReady().then(async () => {
    // Tarayıcıda oturum kalmış ama sunucuda oturum süresi bitmiş ise sayfa ilk açıldığında oturum kontrolü yaparak gerekli eylemi gerçekleştiriyor.
    if (store.getters['auth/getAuthenticated'] === true){
        await store.dispatch('auth/checkAuth').then((status) => {
            if (status === false){
                store.dispatch('auth/logoutUser')
            }
        })
    }

    if (store.getters['auth/getUser']) {
        if (router.currentRoute.value.meta.middleware === 'guest'){
            router.replace({ name: 'Index' })
        }
    }else{
        if (router.currentRoute.value.meta.middleware === 'auth'){
            router.replace({ name: 'Index' })
        }
    }

    router.beforeEach((to, from, next) => {
        if (store.getters['auth/getUser']) {
            if (to.matched.some(route => route.meta.middleware === 'guest')){
                next({ name: 'Index' })
            }else{
                next();
            }
        } else {
            if (to.matched.some(route => route.meta.middleware === 'auth')) {
                next({ name: 'Auth.Login' })
            }else{
                next();
            }
        }
    })
})

export default router
