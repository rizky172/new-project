import Vue from 'vue';
import Router from 'vue-router';

// Layouts
import AdminPanelLayout from '../Layouts/AdminPanelLayout.vue';
import FullPageLayout from '../Layouts/FullPageLayout.vue';

// Pages
import Homepage from '../Pages/Homepage.vue';
import Dashboard from '../Pages/Dashboard.vue';
// import Profile from '../Pages/Profile.vue';
import AdminIndex from '../Pages/AdminIndex.vue';
import Login from '../Pages/Login.vue';
import SignUp from '../Pages/SignUp.vue';
import ForgotPassword from '../Pages/ForgotPassword.vue';
import ResetPassword from '../Pages/ResetPassword.vue';

// Partial route
import Report from './Report.js';
import MasterData from './MasterData.js';
import Other from './Other.js';

var defaultRoute = [
    {
        name: 'homepage',
        path: '',
        component: Homepage,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: null, active: true }
            ],
            pageTitle: 'Homepage'
        }
    },
    {
        path: 'landing-page',
        redirect: {
            name: 'dashboard'
        }
    },
    {
        name: 'dashboard',
        path: 'dashboard',
        component: Dashboard,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: null, active: true }
            ],
            pageTitle: 'Dashboard'
        }
    },
    // {
    //     path: 'profile',
    //     component: Profile
    // },
];

defaultRoute = defaultRoute.concat(MasterData, Other, Report);

const router = new Router({
    // mode: 'history',
    base: '/',
    routes: [
        {
            path: '',
            name: 'partials',
            component: AdminPanelLayout,
            meta: {
              requiresAuth: true
            },
            children: defaultRoute
        },
        {
            path: '',
            name: 'full-page',
            component: FullPageLayout,
            children: [
                {
                    name: 'login',
                    path: 'login',
                    component: Login
                },
                // {
                //     name: 'signup',
                //     path: 'signup',
                //     component: SignUp
                // },
                {
                    name: 'forgot-password',
                    path: 'forgot-password',
                    component: ForgotPassword
                },
                {
                    name: 'reset-password',
                    path: 'reset-password',
                    component: ResetPassword
                }
            ]
        },

        // Redirect to 404 page, if no match found
        {
            path: '*',
            redirect: '/'
        }
    ],
})

router.beforeEach((to, from, next) => {
    // console.log('router', Vue.cookie);
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        // Check cookies, if not found then is not login
        var apiToken = Vue.cookie.get('api_token');
        var isLoggedIn = apiToken !== null && apiToken !== "";
        // console.log('is login', isLoggedIn);

        if (!isLoggedIn) {
            next({ name: 'login' })
        } else {
            next() // go to wherever I'm going
        }
    } else {
        // console.log(to, 'each');
        next() // does not require auth, make sure to always call next()!
    }
})

export default router