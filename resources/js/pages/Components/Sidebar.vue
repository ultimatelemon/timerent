<template>
  <!--
    This example requires updating your template:

    ```
    <html class="h-full bg-white">
    <body class="h-full">
    ```
  -->
  <div v-if="user && current_venue" class="">
    <TransitionRoot as="template" :show="sidebarOpen">
      <Dialog class="relative z-50 lg:hidden" @close="sidebarOpen = false">
        <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0"
                         enter-to="opacity-100" leave="transition-opacity ease-linear duration-300"
                         leave-from="opacity-100" leave-to="opacity-0">
          <div class="fixed inset-0 bg-gray-900/80"/>
        </TransitionChild>
        <div class="fixed inset-0 flex">
          <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                           enter-from="-translate-x-full" enter-to="translate-x-0"
                           leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                           leave-to="-translate-x-full">
            <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
              <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
                               enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100"
                               leave-to="opacity-0">
                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                  <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                    <span class="sr-only">Close sidebar</span>
                    <component :is="XMarkIcon" class="h-6 w-6"></component>
                  </button>
                </div>
              </TransitionChild>
              <!-- Sidebar component, swap this element with another sidebar if you like -->
              <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2 dark:bg-slate-900">
                <div class="flex h-16 shrink-0 items-center">
                  <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                       alt="Your Company"/>
                  <span class="ml-3 font-semibold">Timerent</span>
                </div>
                <nav class="flex flex-1 flex-col">
                  <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                      <ul role="list" class="-mx-2 space-y-1">
                        <li v-for="item in navigation" :key="item.name">
                          <div>
                            <router-link :to="item.link" v-if="!item.children && (item.permission.length > 0 ? (user.owner ? true : hasCommon(item.permission, role.flags)) : true)"
                                         active-class="bg-gray-100 dark:bg-slate-800"
                                         class="hover:bg-gray-100 block rounded-md py-2 pl-10 pr-2 text-sm font-semibold leading-6 text-gray-700 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-800">
                              {{
                                item.name
                              }}
                            </router-link>
                            <Disclosure as="div" v-if="item.children && (item.permission.length > 0 ? (user.owner ? true : hasCommon(item.permission, role.flags)) : true)" :to="item.link" v-slot="{ open }">
                              <DisclosureButton
                                  :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'dark:text-white flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold leading-6 text-gray-700 dark:hover:bg-slate-800']">
                                <ChevronRightIcon
                                    :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'h-5 w-5 shrink-0']"
                                    aria-hidden="true"/>
                                {{ item.name }}
                              </DisclosureButton>
                              <DisclosurePanel as="ul" class="mt-1 px-2">
                                <li v-for="subItem in item.children" :key="subItem.name">
                                  <router-link @click="!open" :to="subItem.link"
                                               active-class="bg-gray-100 dark:bg-slate-800"
                                               class="hover:bg-gray-100 block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-white dark:hover:bg-slate-800">
                                    {{ subItem.name }}
                                  </router-link>
                                </li>
                              </DisclosurePanel>
                            </Disclosure>
                          </div>
                        </li>
                      </ul>
                    </li>
                    <li class="mt-auto">
                      <div class="text-xs font-semibold leading-6 text-gray-400 dark:text-white">Externe navigatie</div>
                      <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li v-for="item in externalNaviation" :key="item.name">
                          <a :href="item.href"
                             class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold dark:hover:bg-slate-800 dark:text-slate-400" target="_blank">
                            <span class="truncate">{{ item.name }}</span>
                          </a>
                        </li>
                      </ul>
                    </li>
                    <li class="-mx-6 mt-auto relative">
                      <!-- Dropdown Menu -->
                      <transition name="fade">
                        <div
                            v-if="optionsVisible"
                            @click.stop
                            ref="dropdownContainer"
                            class="absolute right-0 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-3 -top-28 text-sm space-y-2 transition-all"
                        >
                          <button
                              @click="toggleTheme"
                              class="flex items-center gap-2 px-3 py-2 w-full text-left rounded-lg transition hover:bg-gray-100 dark:hover:bg-gray-700"
                          >
                            <i class="fa" :class="theme === 'light' || !theme ? 'fa-moon' : 'fa-sun'"></i>
                            <span>{{ theme === 'light' || !theme ? 'Dark Mode' : 'Light Mode' }}</span>
                          </button>

                          <button
                              @click="logout"
                              class="flex items-center gap-2 px-3 py-2 w-full text-left rounded-lg transition hover:bg-red-100 dark:hover:bg-red-700 text-red-600 dark:text-red-400"
                          >
                            <i class="fa fa-sign-out"></i>
                            <span>Afmelden</span>
                          </button>
                        </div>
                      </transition>

                      <!-- Profile Section -->
                      <div class="flex items-center border-t gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 justify-between">
                        <div class="flex flex-col">
                          <span class="sr-only">Your profile</span>
                          <span>{{ user.user.name }}</span>
                          <span class="font-light text-xs">{{ user.user.email }}</span>
                        </div>
                        <div class="cursor-pointer" @click="toggleDropdown">
                          <i>
                            <component :is="EllipsisVerticalIcon" class="h-6 w-6"></component>
                          </i>
                        </div>
                      </div>
                    </li>
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
      <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 dark:bg-slate-900 dark:text-white dark:border-slate-950">
        <div class="flex h-16 shrink-0 items-center">
          <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
               alt="Your Company"/>
          <span class="ml-3 font-semibold">{{ current_venue.name }}</span>
        </div>
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
              <ul role="list" class="-mx-2 space-y-1">
                <li v-for="item in navigation" :key="item.name">
                  <div>
                    <router-link :to="item.link" v-if="!item.children && (item.permission.length > 0 ? (user.owner ? true : hasCommon(item.permission, role.flags)) : true)"
                                 active-class="bg-gray-100 dark:bg-slate-800"
                                 class="hover:bg-gray-100 block rounded-md py-2 pl-10 pr-2 text-sm font-semibold leading-6 text-gray-700 dark:text-white dark:hover:bg-slate-800">
                      {{
                        item.name
                      }}
                    </router-link>
                    <Disclosure as="div" v-if="item.children && (item.permission.length > 0 ? (user.owner ? true : hasCommon(item.permission, role.flags)) : true)" :to="item.link" v-slot="{ open }">
                      <DisclosureButton
                          :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'dark:text-white flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold leading-6 text-gray-700 dark:hover:bg-slate-800']">
                        <ChevronRightIcon
                            :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'h-5 w-5 shrink-0 dark:text-white']"
                            aria-hidden="true"/>
                        {{ item.name }}
                      </DisclosureButton>
                      <DisclosurePanel as="ul" class="mt-1 px-2">
                        <li v-for="subItem in item.children" :key="subItem.name">
                          <router-link @click="!open" :to="subItem.link"
                                       active-class="bg-gray-100 dark:bg-slate-800"
                                       class="hover:bg-gray-100 block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700 dark:text-white dark:hover:bg-slate-800">
                            {{ subItem.name }}
                          </router-link>
                        </li>
                      </DisclosurePanel>
                    </Disclosure>
                  </div>
                </li>
              </ul>
            </li>

            <li class="mt-auto cursor-pointer">
              <div class="text-xs font-semibold leading-6 text-gray-400 dark:text-white">Externe navigatie</div>
              <ul role="list" class="-mx-2 mt-2 space-y-1">
                <li v-for="item in externalNaviation" :key="item.name">
                  <a :href="item.href"
                     class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold dark:text-slate-400 dark:hover:bg-slate-800" target="_blank">
                    <span class="truncate">{{ item.name }}</span>
                  </a>
                </li>
              </ul>
            </li>

            <li class="-mx-6 mt-auto relative">
              <!-- Dropdown Menu -->
              <transition name="fade">
                <div
                    v-if="optionsVisible"
                    @click.stop
                    ref="dropdownContainer"
                    class="absolute right-0 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-3 -top-40 text-sm space-y-2 transition-all"
                >
                  <button
                      @click="toggleTheme"
                      class="flex items-center gap-2 px-3 py-2 w-full text-left rounded-lg transition hover:bg-gray-100 dark:hover:bg-gray-700"
                  >
                    <i class="fa" :class="theme === 'light' || !theme ? 'fa-moon' : 'fa-sun'"></i>
                    <span>{{ theme === 'light' || !theme ? 'Dark Mode' : 'Light Mode' }}</span>
                  </button>

                  <button
                      @click="toggleTheme"
                      class="flex items-center gap-2 px-3 py-2 w-full text-left rounded-lg transition hover:bg-gray-100 dark:hover:bg-gray-700"
                  >
                    <i class="fa fa-cog"></i>
                    <span>Mijn instellingen</span>
                  </button>

                  <button
                      @click="logout"
                      class="flex items-center gap-2 px-3 py-2 w-full text-left rounded-lg transition hover:bg-red-100 dark:hover:bg-red-700 text-red-600 dark:text-red-400"
                  >
                    <i class="fa fa-sign-out"></i>
                    <span>Afmelden</span>
                  </button>
                </div>
              </transition>

              <!-- Profile Section -->
              <div class="flex items-center border-t gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 justify-between">
                <div class="flex flex-col">
                  <span class="sr-only">Your profile</span>
                  <span>{{ user.user.name }}</span>
                  <span class="font-light text-xs">{{ user.user.email }}</span>
                </div>
                <div class="cursor-pointer" @click="toggleDropdown">
                  <i>
                    <component :is="EllipsisVerticalIcon" class="h-6 w-6"></component>
                  </i>
                </div>
              </div>
            </li>


            <!--            <li class="-mx-6 mt-auto cursor-pointer" @click="logout">-->
<!--              <div-->
<!--                  class="flex items-center border-t gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50 justify-between dark:border-slate-950">-->
<!--                &lt;!&ndash;                <img class="h-8 w-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />&ndash;&gt;-->
<!--                <div class="flex flex-col">-->
<!--                  <span class="sr-only dark:text-white">Your profile</span>-->
<!--                  <span class="dark:text-white" aria-hidden="true">{{ user.user.name }}</span>-->
<!--                  <span aria-hidden="true" class="font-light text-xs dark:text-white">{{ user.user.email }}</span>-->
<!--                </div>-->
<!--                <div>-->
<!--                  <i>-->
<!--                    <component :is="ArrowRightEndOnRectangleIcon" class="h-6 w-6 text-red-500"></component>-->
<!--                  </i>-->
<!--                </div>-->
<!--              </div>-->
<!--            </li>-->
          </ul>
        </nav>
      </div>
    </div>


    <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow-sm sm:px-6 lg:hidden dark:bg-slate-900">
      <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden dark:text-white" @click="sidebarOpen = true">
        <span class="sr-only">Open sidebar</span>
        <component :is="Bars3Icon" class="h-6 w-6"></component>
      </button>
      <div class="flex-1 text-sm font-semibold leading-6 text-gray-900 dark:text-white">Dashboard</div>
      <a href="#">
        <span class="sr-only">Your profile</span>
        <!--        <img class="h-8 w-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />-->
      </a>
    </div>

    <main class="py-10 lg:pl-72 dark:bg-slate-900">
      <div class="px-4 sm:px-6 lg:px-8">
        <router-view></router-view>
      </div>
    </main>
  </div>
</template>

<script setup>
import {ref} from 'vue'
import {
  Dialog,
  DialogPanel,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  TransitionChild,
  TransitionRoot
} from '@headlessui/vue'
import {HomeIcon, ArrowRightEndOnRectangleIcon, ArrowTopRightOnSquareIcon} from "@heroicons/vue/24/outline/index.js";
import {Bars3Icon, EllipsisVerticalIcon, XMarkIcon} from "@heroicons/vue/16/solid/index.js";

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
  CalendarDaysIcon,
  ListBulletIcon,
  BriefcaseIcon,
  CogIcon,
  ChartBarIcon,
  UsersIcon,
  DocumentIcon,
  UserCircleIcon,
  ChevronRightIcon
} from "@heroicons/vue/24/outline/index.js";

export default {
  name: "Sidebar",
  components: {ChevronRightIcon},
  data() {
    return {
      optionsVisible: false,
      theme: window.localStorage.getItem("theme"),
      user: null,
      role: null,
      navigation: [],
      venue: null,
      externalNaviation: [],
    }
  },

  methods: {

    toggleTheme() {
      if(window.localStorage.getItem('theme') === 'dark') {
        window.localStorage.setItem('theme', 'light')
        document.documentElement.classList.remove('dark');
        this.theme = 'light'
      } else {
        window.localStorage.setItem('theme', 'dark')
        this.theme = 'dark'
        document.documentElement.classList.add('dark');
      }
    },

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
      this.externalNaviation = [
        {
          name: 'Bekijk je pagina',
          href: "https://" + this.current_venue.subdomain + ".timerentapp.nl",
        },
        {
          name: 'Mijn dashboard',
          href: '/select',
        },
      ];

      this.navigation = [
        {
          name: 'Dashboard',
          permission: [],
          link: {name: 'venues.home', params: {venue: this.$store.state.venue.id}},
        },
        {
          name: 'Applicatie',
          permission: ['VIEW_UNITS', 'VIEW_TEMPLATES', 'VIEW_AGENDA', 'VIEW_PRODUCTS', 'VIEW_RESERVATIONS'],
          children: [
            {
              name: 'Units',
              link: {name: 'venues.units.index', params: {venue: this.$store.state.venue.id}},
              icon: CircleStackIcon,
              permission: ['VIEW_UNITS'],
            },
            {
              name: 'Templates',
              link: {name: 'venues.templates.index', params: {venue: this.$store.state.venue.id}},
              icon: ListBulletIcon,
              permission: ['VIEW_TEMPLATES'],
            },
            {
              name: 'Producten',
              link: {name: 'venues.products.index', params: {venue: this.$store.state.venue.id}},
              icon: ArchiveBoxIcon,
              permission: ['VIEW_PRODUCTS'],
            },
            {
              name: 'Agenda',
              link: {name: 'venues.calendar.index', params: {venue: this.$store.state.venue.id}},
              icon: CalendarDaysIcon,
              permission: ['VIEW_AGENDA'],
            },
            {
              name: 'Reserveringen',
              link: {name: 'venues.reservations.index', params: {venue: this.$store.state.venue.id}},
              icon: BriefcaseIcon,
              permission: ['VIEW_RESERVATIONS'],
            },
          ],
        },
        {
          name: 'Beheer',
          permission: ['VIEW_SETTINGS'],
          children: [
            {
              name: 'Algemene instellingen',
              link: {name: 'venues.settings.index', params: {venue: this.$store.state.venue.id}},
              icon: CogIcon,
              permission: ['VIEW_SETTINGS'],
            },
            {
              name: 'Finance instellingen',
              link: {name: 'venues.settings.finance', params: {venue: this.$store.state.venue.id}},
              icon: CogIcon,
              permission: ['VIEW_SETTINGS'],
            },
            {
              name: 'Reservering instellingen',
              link: {name: 'venues.settings.reservations', params: {venue: this.$store.state.venue.id}},
              icon: CogIcon,
              permission: ['VIEW_SETTINGS'],
            },
          ]
        },
        {
          name: 'Financieel',
          permission: ['VIEW_REPORTS', 'VIEW_INVOICES'],
          children: [
            {
              name: 'Rapportage',
              link: {name: 'venues.finance.reports.index', params: {venue: this.$store.state.venue.id}},
              icon: ChartBarIcon,
              permission: ['VIEW_REPORTS'],
            },
            {
              name: 'Facturen',
              link: {name: 'venues.finance.invoices.index', params: {venue: this.$store.state.venue.id}},
              icon: DocumentIcon,
              permission: ['VIEW_INVOICES'],
            }
          ]
        },
        {
          name: 'Gebruikers',
          permission: ['VIEW_EMPLOYEES', 'VIEW_ROLES', 'VIEW_MEMBERS'],
          children: [
            {
              name: 'Medewerkers',
              link: {name: 'venues.users.index', params: {venue: this.$store.state.venue.id}},
              icon: UserIcon,
              permission: ['VIEW_EMPLOYEES'],
            },
            {
              name: 'Medewerker rollen',
              link: {name: 'venues.roles.index', params: {venue: this.$store.state.venue.id}},
              icon: UserCircleIcon,
              permission: ['VIEW_ROLES'],
            },
            {
              name: 'Members',
              link: {name: 'venues.members.index', params: {venue: this.$store.state.venue.id}},
              icon: UserIcon,
              permission: ['VIEW_MEMBERS'],
            },
            {
              name: 'Member groepen',
              link: {name: 'venues.groups.index', params: {venue: this.$store.state.venue.id}},
              icon: UserCircleIcon,
              permission: ['VIEW_GROUPS'],
            }
          ]
        },
        {
          name: 'Help',
          permission: ['VIEW_TICKETS'],
          children: [
            {
              name: 'Questie',
              link: {name: 'venues.support.tickets.index', params: {venue: this.$store.state.venue.id}},
              icon: UserCircleIcon,
              permission: ['VIEW_TICKETS'],
            },
            {
              name: 'Gesloten vragen',
              link: {name: 'venues.support.tickets.archive', params: {venue: this.$store.state.venue.id}},
              icon: UserCircleIcon,
              permission: ['VIEW_TICKETS'],
            }
          ]
        },
        // {
        //   name: 'Modules',
        //   permission: [],
        //   children: [
        //     {
        //       name: 'Alle modules',
        //       link: {name: 'venues.modules.index', params: {venue: this.$store.state.venue.id}},
        //     }
        //   ]
        // },
      ]
    },

    logout() {
      axios.post('/sanctum/logout')
          .then(response => {
            window.location.href = '/login'
          })
    },

    closeDropdown(event) {
      if(this.$refs.dropdownContainer && !this.$refs.dropdownContainer.contains(event.target)) {
        this.optionsVisible = false;
      }
    },

    toggleDropdown(event) {
      event.stopPropagation()
      this.optionsVisible = !this.optionsVisible;
    },
  },

  mounted() {
    this.fetchUser(this.$store.state.venue.id);
    document.addEventListener('click', this.closeDropdown);
  },

  beforeUnmount() {
    document.removeEventListener('click', this.closeDropdown);
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