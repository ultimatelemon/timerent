import Wrapper from "./Wrapper.vue";

export const routes = [
    { path: '/confirmation/:reservation', component: () => import('./pages/ReservationConfirmation.vue'), name: 'confirmation', meta: { title: "confirmation" } },

    { path: '/email/verify', component: () => import('./pages/Auth/EmailVerify.vue'), name: 'email.verify', meta: { title: "pages.email-verify" } },
    { path: '/login', component: () => import('./pages/Auth/Login.vue'), name: 'login', meta: { title: "pages.login" } },
    { path: '/register', component: () => import('./pages/Auth/Register.vue'), name: 'register', meta: { title: "pages.register" } },
    { path: '/select', component: () => import('./pages/VenueSelector.vue'), name: 'venueselect', meta: { title: "pages.login" } },
    { path: '/password-forgot', component: () => import('./pages/Auth/PasswordForgot.vue'), name: 'password-forgot', meta: { title: "pages.login" } },
    { path: '/password-reset', component: () => import('./pages/Auth/PasswordReset.vue'), name: 'password-reset', meta: { title: "pages.login" } },

    { path: '/manage/:venue', component: () => import('./pages/Manage/Venue.vue'), name: 'manage.venue', meta: { title: "pages.login" } },

    { path: '/', redirect: '/select', name: 'index' },

    { path: '/store/:venue/home', component: () => import('./pages/Venues/Home.vue'), name: 'venues.home' },

    {
        path: '/store/:venue/users',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Users/Index.vue'), name: 'venues.users.index' },
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

    {
        path: '/store/:venue/reservations',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Reservations/Index.vue'), name: 'venues.reservations.index' },
            // { path: 'create', component: () => import('./pages/Venues/Products/Edit.vue'), name: 'venues.products.create' },
            { path: ':reservation', component: () => import('./pages/Venues/Reservations/Edit.vue'), name: 'venues.reservations.edit' },
        ]
    },

    { path: '/store/:venue/settings', component: () => import('./pages/Venues/Settings/Index.vue'), name: 'venues.settings.index' },
    { path: '/store/:venue/settings/finance', component: () => import('./pages/Venues/Settings/Finance.vue'), name: 'venues.settings.finance' },
    { path: '/store/:venue/settings/reservations', component: () => import('./pages/Venues/Settings/Reservation.vue'), name: 'venues.settings.reservations' },

    {
        path: '/store/:venue/reports',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Finance/Reports/Index.vue'), name: 'venues.finance.reports.index' },
            { path: 'invoices', component: () => import('./pages/Venues/Finance/Invoices/Index.vue'), name: 'venues.finance.invoices.index' },
        ]
    },

    {
        path: '/store/:venue/invoices',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Finance/Invoices/Index.vue'), name: 'venues.finance.invoices.index' },
            { path: ':invoice', component: () => import('./pages/Venues/Finance/Invoices/Show.vue'), name: 'venues.finance.invoices.show' },
        ]
    },

    // Roles

    {
        path: '/store/:venue/roles',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Roles/Index.vue'), name: 'venues.roles.index' },
            { path: 'create', component: () => import('./pages/Venues/Roles/Edit.vue'), name: 'venues.roles.create' },
            { path: ':role', component: () => import('./pages/Venues/Roles/Edit.vue'), name: 'venues.roles.edit' },
            // { path: ':member/reservations', component: () => import('./pages/Venues/Members/Reservations.vue'), name: 'venues.members.reservations' },
        ]
    },

    // Members

    {
        path: '/store/:venue/members',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Members/Index.vue'), name: 'venues.members.index' },
            // { path: 'create', component: () => import('./pages/Venues/Products/Edit.vue'), name: 'venues.products.create' },
            { path: ':member', component: () => import('./pages/Venues/Members/Edit.vue'), name: 'venues.members.edit' },
            { path: ':member/reservations', component: () => import('./pages/Venues/Members/Reservations.vue'), name: 'venues.members.reservations' },
        ]
    },

    // Member groups

    {
        path: '/store/:venue/groups',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Groups/Index.vue'), name: 'venues.groups.index' },
            { path: 'create', component: () => import('./pages/Venues/Groups/Edit.vue'), name: 'venues.groups.create' },
            { path: ':group', component: () => import('./pages/Venues/Groups/Edit.vue'), name: 'venues.groups.edit' },
        ]
    },

    {
        path: '/store/:venue/support/tickets',
        component: Wrapper,
        children: [
            { path: '', component: () => import('./pages/Venues/Support/Tickets/Index.vue'), name: 'venues.support.tickets.index' },
            { path: 'create', component: () => import('./pages/Venues/Support/Tickets/Create.vue'), name: 'venues.support.tickets.create' },
            { path: 'archive', component: () => import('./pages/Venues/Support/Tickets/Archive.vue'), name: 'venues.support.tickets.archive' },
            { path: ':ticket', component: () => import('./pages/Venues/Support/Tickets/Show.vue'), name: 'venues.support.tickets.show' },
        ]
    },



    // Payments
    {
        path: '/timerentpayments/success',
        component: () => import('./pages/StripeConnectSuccess.vue'),
        name: 'stripe.connect.success'
    },
];