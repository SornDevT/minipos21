import { createWebHistory, createRouter } from "vue-router";

import Store from "../Pages/Store.vue";
import Pos from "../Pages/Pos.vue";
import Transection from "../Pages/Transection.vue";
import Report from "../Pages/Report.vue";
import Login from "../Pages/Login.vue";
import Register from "../Pages/Register.vue"
import { useStore } from "../Store/Auth";


// created meddleware from = /login, to = /

const authMiddleware = (to, from , next) => {

    const token = localStorage.getItem('web_token');
    const user = localStorage.getItem('web_user');
    const store = useStore();

    if(token){
        // ຖ້າມີ token ໃນ localstorage ເກັບໄວ້ໃນ pinia
        store.set_token(token);
        store.set_user(user);
        next();
    } else {
        // console.log('Go to login')
        // ບໍ່ມີ token
        next({
            path:'/login',
            replace: true
        })
    }

}



export const routes = [
    {
        path:'/',
        redirect:'/store'
    },
    {
        name: 'store',
        path: '/store',
        component: Store,
        meta:{
            middleware: [authMiddleware]
        }
    },
    {
        name: 'pos',
        path: '/Pos',
        component: Pos,
        meta:{
            middleware: [authMiddleware]
        }
    },
    {
        name: 'Transection',
        path: '/transection',
        component: Transection,
        meta:{
            middleware: [authMiddleware]
        }
    },{
        name: 'Report',
        path: '/report',
        component: Report,
        meta:{
            middleware: [authMiddleware]
        }
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


router.beforeEach((to,from,next)=>{
    
    const token = localStorage.getItem('web_token');

    if(to.meta.middleware){
        to.meta.middleware.forEach(middleware=>middleware(to,from,next))
    } else {
        if(to.path == '/login' || to.path == '/'){
            if(token){
                next({
                    path:'/store',
                    replace: true
                })
            } else {
                next();
            }
        } else {
            next();
        }
    }
});



export default router;
