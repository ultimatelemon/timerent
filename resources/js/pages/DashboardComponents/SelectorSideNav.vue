<template>
  <!--
    This example requires updating your template:

    ```
    <html class="h-full bg-white">
    <body class="h-full">
    ```
  -->
  <div v-if="user">

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
              <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2">
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
                            <router-link :to="item.link" v-if="!item.children"
                                         active-class="bg-gray-100"
                                         class="hover:bg-gray-100 block rounded-md py-2 pl-10 pr-2 text-sm font-semibold leading-6 text-gray-700">
                              {{
                                item.name
                              }}
                            </router-link>
                            <Disclosure as="div" v-if="item.children" :to="item.link" v-slot="{ open }">
                              <DisclosureButton
                                  :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold leading-6 text-gray-700']">
                                <ChevronRightIcon
                                    :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'h-5 w-5 shrink-0']"
                                    aria-hidden="true"/>
                                {{ item.name }}
                              </DisclosureButton>
                              <DisclosurePanel as="ul" class="mt-1 px-2">
                                <li v-for="subItem in item.children" :key="subItem.name">
                                  <router-link @click="!open" :to="subItem.link"
                                               active-class="bg-gray-100"
                                               class="hover:bg-gray-100 block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700">
                                    {{ subItem.name }}
                                  </router-link>
                                </li>
                              </DisclosurePanel>
                            </Disclosure>
                          </div>
                        </li>
                      </ul>
                    </li>
                    <li class="-mx-6 mt-auto cursor-pointer" @click="logout">
                      <div
                          class="flex items-center border-t gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50 justify-between">
                        <!--                <img class="h-8 w-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />-->
                        <div class="flex flex-col">
                          <span class="sr-only">Your profile</span>
                          <span aria-hidden="true">{{ user.name }}</span>
                          <span aria-hidden="true" class="font-light text-xs">{{ user.email }}</span>
                        </div>
                        <div>
                          <i>
                            <component :is="ArrowRightEndOnRectangleIcon" class="h-6 w-6 text-red-500"></component>
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
      <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
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
                    <router-link :to="item.link" v-if="!item.children"
                                 active-class="bg-gray-100"
                                 class="hover:bg-gray-100 block rounded-md py-2 pl-10 pr-2 text-sm font-semibold leading-6 text-gray-700">
                      {{
                        item.name
                      }}
                    </router-link>
                    <Disclosure as="div" v-if="item.children" :to="item.link" v-slot="{ open }">
                      <DisclosureButton
                          :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold leading-6 text-gray-700']">
                        <ChevronRightIcon
                            :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'h-5 w-5 shrink-0']"
                            aria-hidden="true"/>
                        {{ item.name }}
                      </DisclosureButton>
                      <DisclosurePanel as="ul" class="mt-1 px-2">
                        <li v-for="subItem in item.children" :key="subItem.name">
                          <router-link @click="!open" :to="subItem.link"
                                       active-class="bg-gray-100"
                                       class="hover:bg-gray-100 block rounded-md py-2 pl-9 pr-2 text-sm leading-6 text-gray-700">
                            {{ subItem.name }}
                          </router-link>
                        </li>
                      </DisclosurePanel>
                    </Disclosure>
                  </div>
                </li>
              </ul>
            </li>
            <li class="-mx-6 mt-auto cursor-pointer" @click="logout">
              <div
                  class="flex items-center border-t gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50 justify-between">
                <!--                <img class="h-8 w-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />-->
                <div class="flex flex-col">
                  <span class="sr-only">Your profile</span>
                  <span aria-hidden="true">{{ user.name }}</span>
                  <span aria-hidden="true" class="font-light text-xs">{{ user.email }}</span>
                </div>
                <div>
                  <i>
                    <component :is="ArrowRightEndOnRectangleIcon" class="h-6 w-6 text-red-500"></component>
                  </i>
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
import {
  Dialog,
  DialogPanel,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import {HomeIcon, ArrowRightEndOnRectangleIcon, ArrowTopRightOnSquareIcon} from "@heroicons/vue/24/outline/index.js";
import {Bars3Icon, XMarkIcon, ChevronRightIcon} from "@heroicons/vue/16/solid/index.js";

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
  name: "Sidebar",
  data() {
    return {
      user: null,
      role: null,
      venue: null,
      navigation: [
        { name: 'Dashboard', link: {name: 'venueselect'}},
        // {
        //   name: 'Support',
        //   children: [
        //     { name: 'Kennisbank', link: {name: 'index'} },
        //     { name: 'Support tickets', link: {name: 'index'} },
        //   ],
        // },
        // { name: 'Instellingen', link: {name: 'index'},},
      ],
    }
  },

  methods: {

    fetchUser() {
      axios.get('/users/current')
          .then(response => {
            this.user = response.data.data;
            console.log(response.data.data);
            // this.role = response.data.data.role;
          })
          .catch(e => {
            console.log(e.message)
          })
    },

    // fetchUser(venue) {
    //   axios.get('/users/current?venue=' + venue)
    //       .then(response => {
    //         this.user = response.data.data.user;
    //         this.role = response.data.data.role;
    //         // this.fetchNavigation();
    //       })
    //       .catch(e => {
    //         console.log(e.message)
    //       })
    // },

    // fetchUser() {
    //   axios.get('/users/current')
    //       .then(response => {
    //         // this.current_user = response.data.data.user;
    //         this.current_user_role = response.data.role;
    //         // this.fetchData();
    //       })
    // },

    hasCommon(permission, flags) {
      return flags.some(item1 => permission.some(item2 => item1 === item2));
    },

    logout() {
      axios.post('/sanctum/logout')
          .then(response => {
            window.location.href = '/login'
          })
    },
  },

  mounted() {
    this.fetchUser();
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