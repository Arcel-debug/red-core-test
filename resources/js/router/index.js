window.Vue = require('vue').default;
import VueRouter from 'vue-router';
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import Users from '../components/User/Users.vue';
import Roles from '../components/Role/Roles.vue';
import UserEditForm from '../components/User/UserEditForm.vue';
import RoleForm from '../components/Role/RoleForm.vue';

Vue.use(VueRouter);

const routes = [
    {
        name: 'login',
        path: '/login',
        component: Login,
    },
    {
        name: 'register',
        path: '/register',
        component: Register,
    },
    {
        name: 'users',
        path: '/',
        component: Users,
    },
    {
        name: 'roles',
        path: '/roles',
        component: Roles,
    }
];

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
});

// function isLoggedIn() {
//     return sessionStorage.getItem("auth");
// }

// router.beforeEach((to, from, next) => {
//     if (to.matched.some(record => record.meta.authOnly)) {
//       // this route requires auth, check if logged in
//       // if not, redirect to login page.
//       if (!isLoggedIn()) {
//         next({
//           path: "/login",
//           query: { redirect: to.fullPath }
//         });
//       } else {
//         next();
//       }
//     } else if (to.matched.some(record => record.meta.guestOnly)) {
//       // this route requires auth, check if logged in
//       // if not, redirect to login page.
//       if (isLoggedIn()) {
//         next({
//           path: "/",
//           query: { redirect: to.fullPath }
//         });
//       } else {
//         next();
//       }
//     } else {
//       next(); // make sure to always call next()!
//     }
// });

export default router;