export const ApplicationRoutes = [
    { path: '/', component: () => import('./pages/Application/Home.vue'), name: 'application.home', meta: { title: "pages.login" } },
    { path: '/login', component: () => import('./pages/Application/Auth/Login.vue'), name: 'application.login', meta: { title: "pages.login" } },
    { path: '/password-forgot', component: () => import('./pages/Application/Auth/PasswordForgot.vue'), name: 'application.password-forgot', meta: { title: "pages.login" } },
    { path: '/password-reset', component: () => import('./pages/Application/Auth/PasswordReset.vue'), name: 'application.password-reset', meta: { title: "pages.login" } },
    { path: '/register', component: () => import('./pages/Application/Auth/Register.vue'), name: 'application.register', meta: { title: "pages.login" } },
    { path: '/email/verify', component: () => import('./pages/Application/Auth/EmailVerify.vue'), name: 'application.email.verify', meta: { title: "pages.email-verify" } },
]