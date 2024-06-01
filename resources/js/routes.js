import Wrapper from "./Wrapper.vue";

export const routes = [
    { path: '/login', component: () => import('./pages/Auth/Login.vue'), name: 'login', meta: { title: "pages.login" } },
    { path: '/', component: () => import('./pages/Venues/Home.vue'), name: 'index', meta: { title: "pages.login" } },
    { path: '/select', component: () => import('./pages/VenueSelector.vue'), name: 'venueselect', meta: { title: "pages.login" } },

    { path: '/store/:venue/home', component: () => import('./pages/Venues/Home.vue'), name: 'venues.home' },

    {
        path: '/users',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Users/UserIndex.vue'), name: 'users.index' },
        ]
    },

    {
        path: '/store/:venue/units',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Units/Index.vue'), name: 'venues.units.index' },
            { path: 'create', component: () => import('./pages/Venues/Units/Edit.vue'), name: 'venues.units.create' },
            { path: ':unit', component: () => import('./pages/Venues/Units/Edit.vue'), name: 'venues.units.edit' },
        ]
    },

    {
        path: '/store/:venue/templates',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Templates/Index.vue'), name: 'venues.templates.index' },
            { path: 'create', component: () => import('./pages/Venues/Templates/Edit.vue'), name: 'venues.templates.create' },
            { path: ':template', component: () => import('./pages/Venues/Templates/Edit.vue'), name: 'venues.templates.edit' },
        ]
    },

    { path: '/store/:venue/calendar', component: () => import('./pages/Venues/Calendar/Index.vue'), name: 'venues.calendar.index' },

    {
        path: '/store/:venue/products',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Products/Index.vue'), name: 'venues.products.index' },
            { path: 'create', component: () => import('./pages/Venues/Products/Edit.vue'), name: 'venues.products.create' },
            { path: ':product', component: () => import('./pages/Venues/Products/Edit.vue'), name: 'venues.products.edit' },
        ]
    },
];