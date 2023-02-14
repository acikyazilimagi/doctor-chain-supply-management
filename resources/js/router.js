import { createRouter, createWebHistory } from 'vue-router';
import store from '@/src/store/index.js'


const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "Index",
            component: () => import(/* webpackChunkName: "Index" */ '@/src/pages/Index.vue'),
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
            // beforeEnter: (to, from, next) => {
            //     if (store.getters['auth/getAuthenticated']) {
            //         next()
            //     } else {
            //         next({ name: 'Auth.Login' })
            //     } 
            // },
        },
        {
            path: '/yeni-istek-olustur',
            component: () => import(/* webpackChunkName: "AccountRecipeCreate" */ '@/src/pages/Account/Recipe/Create.vue'),
            name: 'Recipes.Create',
            meta: {
                layout: 'FullWidth',
                middleware: "auth"
            },
            beforeEnter: (to, from, next) => {
                if (store.getters['auth/getAuthenticated']) {
                    next()
                } else {
                    next({ name: 'Auth.Login' })
                }
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
            beforeEnter: (to, from, next) => {
                if (store.getters['auth/getAuthenticated']) {
                    next()
                } else {
                    next({ name: 'Auth.Login' })
                }
            },
        },
        {
            path: '/auth/login',
            component: () => import(/* webpackChunkName: "AuthLogin" */ '@/src/pages/Auth/Login.vue'),
            name: 'Auth.Login',
            meta: {
                layout: 'FullWidth',
                middleware: "guest"
            },
            beforeEnter: (to, from, next) => {
                if (store.getters['auth/getAuthenticated']) {
                    next({ name: 'Index' })
                } else {
                    next()
                }
            },
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
            },
            beforeEnter: (to, from, next) => {
                if (store.getters['auth/getAuthenticated']) {
                    next()
                } else {
                    next({ name: 'Auth.Login' })
                }
            },
        },
        {
            path: '/hesabimi-guncelle',
            component: () => import(/* webpackChunkName: "AccountEdit" */ '@/src/pages/Account/Edit.vue'),
            name: 'Account.Profile.Edit',
            meta: {
                middleware: "auth"
            },
            beforeEnter: (to, from, next) => {
                if (store.getters['auth/getAuthenticated']) {
                    next()
                } else {
                    next({ name: 'Auth.Login' })
                }
            },
        },
        {
            path: '/:pathMatch(.*)*',
            redirect: '/',
        }
    ],
});

export default router;


