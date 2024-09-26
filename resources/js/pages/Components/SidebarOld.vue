<template>
  <!--
    This example requires updating your template:

    ```
    <html class="h-full bg-white">
    <body class="h-full">
    ```
  -->
  <div v-if="user && current_venue">
    <TransitionRoot as="template" :show="sidebarOpen">
      <Dialog class="relative z-50 lg:hidden" @close="sidebarOpen = false">
        <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
          <div class="fixed inset-0 bg-gray-900/80" />
        </TransitionChild>
        <div class="fixed inset-0 flex">
          <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
            <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
              <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                  <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                    <span class="sr-only">Close sidebar</span>
                    <component :is="XMarkIcon" class="h-6 w-6"></component>
                  </button>
                </div>
              </TransitionChild>
              <!-- Sidebar component, swap this element with another sidebar if you like -->
              <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2">
                <div class="flex h-16 shrink-0 items-center">
                  <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />
                  <span class="ml-3 font-semibold">Timerent</span>
                </div>
                <a href="/select">Terug naar select</a>
                <a :href="'https://' + current_venue.subdomain + '.timerent-rewrite.test'" target="_blank" class="flex items-center space-x-2">
                  <p>Bekijk pagina</p>
                  <component :is="ArrowTopRightOnSquareIcon" class="text-gray-400 group-hover:text-indigo-600 h-6 w-6 shrink-0" aria-hidden="true"></component>
                </a>
                <nav class="flex flex-1 flex-col">
                  <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                      <ul role="list" class="-mx-2 space-y-1">
                        <li v-for="item in navigation" :key="item.name">
                          <div v-if="item.type === 'category' && (item.permission.length > 0 ? (user.owner ? true : hasCommon(item.permission, role.flags)) : true)" class="mb-1 mt-3 text-sm flex items-center">
                    <span class="font-bold text-xs pr-1">
                      {{item.name}}
                    </span>
                          </div>
                          <router-link v-else v-if="item.permission.length > 0 ? (user.owner ? true : hasCommon(item.permission, role.flags)) : true" :to="item.link" active-class="bg-gray-50 text-indigo-600" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <component :is="item.icon" class="text-gray-400 group-hover:text-indigo-600 h-6 w-6 shrink-0" aria-hidden="true"></component>
                            <!--                    <i :class="'bx bx-' + item.icon"></i>-->
                            {{item.name}}
                          </router-link>
                        </li>
                      </ul>
                    </li>
                    <!--                    <li>-->
                    <!--                      <div class="text-xs font-semibold leading-6 text-gray-400">Your teams</div>-->
                    <!--                      <ul role="list" class="-mx-2 mt-2 space-y-1">-->
                    <!--                        <li v-for="team in teams" :key="team.name">-->
                    <!--                          <a :href="team.href" :class="[team.current ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">-->
                    <!--                            <span :class="[team.current ? 'text-indigo-600 border-indigo-600' : 'text-gray-400 border-gray-200 group-hover:border-indigo-600 group-hover:text-indigo-600', 'flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border text-[0.625rem] font-medium bg-white']">{{ team.initial }}</span>-->
                    <!--                            <span class="truncate">{{ team.name }}</span>-->
                    <!--                          </a>-->
                    <!--                        </li>-->
                    <!--                      </ul>-->
                    <!--                    </li>-->
                  </ul>
                </nav>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
      <!-- Sidebar component, swap this element with another sidebar if you like -->
      <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
        <div class="flex h-16 shrink-0 items-center">
          <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />
          <span class="ml-3 font-semibold">{{ current_venue.name }}</span>
        </div>
        <a href="/select">Terug naar select</a>
        <a :href="'https://' + current_venue.subdomain + '.timerent-rewrite.test'" target="_blank" class="flex items-center space-x-2">
          <p>Bekijk pagina</p>
          <component :is="ArrowTopRightOnSquareIcon" class="text-gray-400 group-hover:text-indigo-600 h-6 w-6 shrink-0" aria-hidden="true"></component>
        </a>
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
              <ul role="list" class="-mx-2 space-y-1">
                <li v-for="item in navigation" :key="item.name">
                  <div v-if="item.type === 'category' && (item.permission.length > 0 ? (user.owner ? true : hasCommon(item.permission, role.flags)) : true)" class="mb-1 mt-3 text-sm flex items-center">
                    <span class="font-bold text-xs pr-1">
                      {{item.name}}
                    </span>
                  </div>
                  <router-link v-else v-if="item.permission.length > 0 ? (user.owner ? true : hasCommon(item.permission, role.flags)) : true" :to="item.link" active-class="bg-gray-50 text-indigo-600" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                    <component :is="item.icon" class="text-gray-400 group-hover:text-indigo-600 h-6 w-6 shrink-0" aria-hidden="true"></component>
                    <!--                    <i :class="'bx bx-' + item.icon"></i>-->
                    {{item.name}}
                  </router-link>
                </li>
              </ul>
            </li>
            <!--            <li>-->
            <!--              <div class="text-xs font-semibold leading-6 text-gray-400">Your teams</div>-->
            <!--              <ul role="list" class="-mx-2 mt-2 space-y-1">-->
            <!--                <li v-for="team in teams" :key="team.name">-->
            <!--                  <router-link :to="team.href" :class="[team.current ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-50', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">-->
            <!--                    <span :class="[team.current ? 'text-indigo-600 border-indigo-600' : 'text-gray-400 border-gray-200 group-hover:border-indigo-600 group-hover:text-indigo-600', 'flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border text-[0.625rem] font-medium bg-white']">{{ team.initial }}</span>-->
            <!--                    <span class="truncate">{{ team.name }}</span>-->
            <!--                  </router-link>-->
            <!--                </li>-->
            <!--              </ul>-->
            <!--            </li>-->
            <li class="-mx-6 mt-auto cursor-pointer" @click="logout">
              <div class="flex items-center border-t gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50 justify-between">
                <!--                <img class="h-8 w-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />-->
                <div class="flex flex-col">
                  <span class="sr-only">Your profile</span>
                  <span aria-hidden="true">{{ user.user.name }}</span>
                  <span aria-hidden="true" class="font-light text-xs">{{ user.user.email }}</span>
                </div>
                <div>
                  <i><component :is="ArrowRightEndOnRectangleIcon" class="h-6 w-6 text-red-500"></component></i>
                </div>
              </div>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow-sm sm:px-6 lg:hidden">
      <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
        <span class="sr-only">Open sidebar</span>
        <component :is="Bars3Icon" class="h-6 w-6"></component>
      </button>
      <div class="flex-1 text-sm font-semibold leading-6 text-gray-900">Dashboard</div>
      <a href="#">
        <span class="sr-only">Your profile</span>
        <!--        <img class="h-8 w-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />-->
      </a>
    </div>

    <main class="py-10 lg:pl-72">
      <div class="px-4 sm:px-6 lg:px-8">
        <router-view></router-view>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import {HomeIcon, ArrowRightEndOnRectangleIcon, ArrowTopRightOnSquareIcon} from "@heroicons/vue/24/outline/index.js";
import {Bars3Icon, XMarkIcon} from "@heroicons/vue/16/solid/index.js";

// { name: 'Dashboard', href: '#', icon: HomeIcon, current: true },
// { name: 'Team', href: '#', icon: UsersIcon, current: false },
// { name: 'Projects', href: '#', icon: FolderIcon, current: false },
// { name: 'Calendar', href: '#', icon: CalendarIcon, current: false },
// { name: 'Documents', href: '#', icon: DocumentDuplicateIcon, current: false },
// { name: 'Finance', href: '#', icon: ChartPieIcon, current: false },
const teams = [
  // { id: 1, name: 'Heroicons', href: '#', initial: 'H', current: false },
  // { id: 2, name: 'Tailwind Labs', href: '#', initial: 'T', current: false },
  // { id: 3, name: 'Workcation', href: '#', initial: 'W', current: false },
]

const sidebarOpen = ref(false)
</script>

<script>

import {
  HomeIcon,
  UserIcon,
  CircleStackIcon,
  ArchiveBoxIcon,
  CalendarDaysIcon, ListBulletIcon, BriefcaseIcon, CogIcon, ChartBarIcon, UsersIcon, DocumentIcon, UserCircleIcon
} from "@heroicons/vue/24/outline/index.js";
// import {CircleStackIcon} from "@heroicons/vue/16/solid/index.js";

export default {
  name: "SidebarOld",
  data() {
    return {
      user: null,
      role: null,
      navigation: [],
      venue: null,
    }
  },

  methods: {

    // fetchUser() {
    //   axios.get('/users/current')
    //       .then(response => {
    //         this.user = response.data.data;
    //         // this.role = response.data.data.role;
    //         this.fetchNavigation();
    //       })
    //       .catch(e => {
    //         console.log(e.message)
    //       })
    // },

    fetchUser(venue) {
      axios.get('/users/current?venue=' + venue)
          .then(response => {
            this.user = response.data.data.user;
            this.role = response.data.data.role;
            this.fetchNavigation();
          })
          .catch(e => {
            console.log(e.message)
          })
    },

    hasCommon(permission, flags) {
      return flags.some(item1 => permission.some(item2 => item1 === item2));
    },

    fetchNavigation() {
      this.navigation = [
        {
          'type': 'category',
          'name': 'Algemeen',
          'permission': [],
        },
        {
          'name': 'Dashboard',
          'link': {name: 'venues.home', params: {venue: this.$store.state.venue.id}},
          'icon': HomeIcon,
          'permission': [],
        },
        {
          'type': 'category',
          'name': 'Applicatie',
          'permission': ['VIEW_UNITS', 'VIEW_TEMPLATES', 'VIEW_AGENDA', 'VIEW_PRODUCTS', 'VIEW_RESERVATIONS'],
        },
        {
          'name': 'Units',
          'link': {name: 'venues.units.index', params: {venue: this.$store.state.venue.id}},
          'icon': CircleStackIcon,
          'permission': ['VIEW_UNITS'],
        },
        {
          'name': 'Templates',
          'link': {name: 'venues.templates.index', params: {venue: this.$store.state.venue.id}},
          'icon': ListBulletIcon,
          'permission': ['VIEW_TEMPLATES'],
        },
        {
          'name': 'Agenda',
          'link': {name: 'venues.calendar.index', params: {venue: this.$store.state.venue.id}},
          'icon': CalendarDaysIcon,
          'permission': ['VIEW_AGENDA'],
        },
        {
          'name': 'Producten',
          'link': {name: 'venues.products.index', params: {venue: this.$store.state.venue.id}},
          'icon': ArchiveBoxIcon,
          'permission': ['VIEW_PRODUCTS'],
        },
        {
          'name': 'Reserveringen',
          'link': {name: 'venues.reservations.index', params: {venue: this.$store.state.venue.id}},
          'icon': BriefcaseIcon,
          'permission': ['VIEW_RESERVATIONS'],
        },
        {
          'type': 'category',
          'name': 'Beheer',
          'permission': ['VIEW_SETTINGS']
        },
        {
          'name': 'Instellingen',
          'link': {name: 'venues.settings.index', params: {venue: this.$store.state.venue.id}},
          'icon': CogIcon,
          'permission': ['VIEW_SETTINGS'],
        },
        {
          'type': 'category',
          'name': 'Finance',
          'permission': ['VIEW_REPORTS', 'VIEW_INVOICES']
        },
        {
          'name': 'Rapportage',
          'link': {name: 'venues.finance.reports.index', params: {venue: this.$store.state.venue.id}},
          'icon': ChartBarIcon,
          'permission': ['VIEW_REPORTS'],
        },
        {
          'name': 'Facturatie',
          'link': {name: 'venues.finance.invoices.index', params: {venue: this.$store.state.venue.id}},
          'icon': DocumentIcon,
          'permission': ['VIEW_INVOICES'],
        },
        {
          'type': 'category',
          'name': 'Gebruikers',
          'permission': ['VIEW_EMPLOYEES', 'VIEW_ROLES', 'VIEW_MEMBERS']
        },
        {
          'name': 'Medewerkers',
          'link': {name: 'venues.users.index', params: {venue: this.$store.state.venue.id}},
          'icon': UserIcon,
          'permission': ['VIEW_EMPLOYEES'],
        },
        {
          'name': 'Medewerkers rollen',
          'link': {name: 'venues.roles.index', params: {venue: this.$store.state.venue.id}},
          'icon': UserCircleIcon,
          'permission': ['VIEW_ROLES'],
        },
        {
          'name': 'Members',
          'link': {name: 'venues.members.index', params: {venue: this.$store.state.venue.id}},
          'icon': UsersIcon,
          'permission': ['VIEW_MEMBERS'],
        },
        {
          'name': 'Member groepen',
          'link': {name: 'venues.groups.index', params: {venue: this.$store.state.venue.id}},
          'icon': UserCircleIcon,
          'permission': ['VIEW_GROUPS'],
        },
      ]
    },

    logout() {
      axios.post('/sanctum/logout')
          .then(response => {
            window.location.href = '/login'
          })
    },
  },

  mounted() {
    console.log(this.$store.state.venue.id)
    this.fetchUser(this.$store.state.venue.id);
  },

  // TODO: Fix user store in Vuex to remove the api call to fetch user.
  computed: {
    current_venue: {
      get() {
        return this.$store.state.venue;
      }
    },

    current_user: {
      get() {
        return this.$store.state.user;
      }
    }
  }
}
</script>