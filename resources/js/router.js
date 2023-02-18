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
        path: '/auth',
        redirect: '/auth/login',
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
                path: '/auth/password/email',
                component: () => import(/* webpackChunkName: "AuthPasswordReset" */ '@/src/pages/Auth/Password/Email.vue'),
                name: 'Auth.Password.Email',
                meta: {
                    layout: 'FullWidth',
                    middleware: "guest"
                }
            },
            {
                path: '/auth/password/reset/:token',
                component: () => import(/* webpackChunkName: "AuthPasswordReset" */ '@/src/pages/Auth/Password/Reset.vue'),
                name: 'Auth.Password.Reset',
                meta: {
                    layout: 'FullWidth',
                    middleware: "guest"
                }
            },
        ]
    },
    {
        path: '/hesabim',
        redirect: '/hesabim/bilgilerim',
        meta: {
            middleware: "auth"
        },
        component: {
            render() {
                return h(resolveComponent('router-view'))
            },
        },
        children: [
            {
                path: '/hesabim/bilgilerim',
                component: () => import(/* webpackChunkName: "AccountUserProfile" */ '@/src/pages/Account/User/Profile.vue'),
                name: 'Account.Profile.Index',
                meta: {
                    middleware: "auth"
                }
            },
            {
                path: '/hesabim/duzenle',
                component: () => import(/* webpackChunkName: "AccountUserEdit" */ '@/src/pages/Account/User/Edit.vue'),
                name: 'Account.Profile.Edit',
                meta: {
                    middleware: "auth"
                }
            },
            {
                path: '/hesabim/parola-degistir',
                component: () => import(/* webpackChunkName: "AccountUserChangePassword" */ '@/src/pages/Account/User/ChangePassword.vue'),
                name: 'Account.Profile.ChangePassword',
                meta: {
                    middleware: "auth"
                }
            },
            {
                path: '/hesabim/yeni-istek-olustur',
                component: () => import(/* webpackChunkName: "AccountRecipeCreate" */ '@/src/pages/Account/Recipe/Create.vue'),
                name: 'Account.Recipes.Create',
                meta: {
                    layout: 'FullWidth',
                    middleware: "auth"
                },
            },
            {
                path: '/hesabim/istek-listem',
                component: () => import(/* webpackChunkName: "AccountRecipeIndex" */ '@/src/pages/Account/Recipe/Index.vue'),
                name: 'Account.Recipes.Index',
                meta: {
                    layout: 'FullWidth',
                    middleware: "auth"
                },
            },
        ]
    },
    {
        path: '/sayfalar',
        redirect: '/',
        component: {
            render() {
                return h(resolveComponent('router-view'))
            },
        },
        children: [
            {
                path: '/sayfalar/:slug',
                component: () => import(/* webpackChunkName: "ContentsRizaMetni" */ '@/src/pages/DynamicContent.vue'),
                name: 'DynamicContent',
                meta: {
                    layout: 'FullWidth',
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
