import Wrapper from "./Wrapper.vue";

export const ApplicationRoutes = [
    { path: '/', component: () => import('./pages/Application/Home.vue'), name: 'application.home', meta: { title: "pages.login" } },
    { path: '/dashboard', component: () => import('./pages/Application/Dashboard/Home.vue'), name: 'application.dashboard', meta: { title: "pages.login" } },
    { path: '/login', component: () => import('./pages/Application/Auth/Login.vue'), name: 'application.login', meta: { title: "pages.login" } },
    { path: '/password-forgot', component: () => import('./pages/Application/Auth/PasswordForgot.vue'), name: 'application.password-forgot', meta: { title: "pages.login" } },
    { path: '/password-reset', component: () => import('./pages/Application/Auth/PasswordReset.vue'), name: 'application.password-reset', meta: { title: "pages.login" } },
    { path: '/register', component: () => import('./pages/Application/Auth/Register.vue'), name: 'application.register', meta: { title: "pages.login" } },
    { path: '/email/verify', component: () => import('./pages/Application/Auth/EmailVerify.vue'), name: 'application.email.verify', meta: { title: "pages.email-verify" } },

    {
        path: '/dashboard/reservations',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Application/Dashboard/Reservations/Index.vue'), name: 'application.reservations.index' },
            { path: ':reservation', component: () => import('./pages/Application/Dashboard/Reservations/Edit.vue'), name: 'application.reservations.edit' },
        ]
    },

    {
        path: '/dashboard/settings',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Application/Dashboard/Settings/Index.vue'), name: 'application.settings.index' },
            // { path: ':reservation', component: () => import('./pages/Venues/Reservations/Edit.vue'), name: 'venues.reservations.edit' },
        ]
    },
]
