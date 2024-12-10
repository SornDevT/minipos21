import { createWebHistory, createRouter } from "vue-router";

import Store from "../Pages/Store.vue";
import Pos from "../Pages/Pos.vue";
import Transection from "../Pages/Transection.vue";
import Report from "../Pages/Report.vue";
import Login from "../Pages/Login.vue";
import Register from "../Pages/Register.vue"



export const routes = [
    {
        path:'/',
        redirect:'/store'
    },
    {
        name: 'store',
        path: '/store',
        component: Store
    },
    {
        name: 'pos',
        path: '/Pos',
        component: Pos
    },
    {
        name: 'Transection',
        path: '/transection',
        component: Transection
    },{
        name: 'Report',
        path: '/report',
        component: Report
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    // {
    //     name: 'root',
    //     path: '/store',
    //     component: Store
    // },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    scrollBehavior () {
        window.scrollTo(0,0)
    }
});

export default router;
