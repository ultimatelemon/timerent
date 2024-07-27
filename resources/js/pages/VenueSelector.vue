<template>
  <main class="fixed top-0 left-0 right-0 bottom-0 bg-gray-100 flex flex-col">
    <div v-if="current_user" class="leading-tight flex items-center p-5 rounded">
      <router-link to="#" class="cursor-pointer flex items-center mr-auto">
<!--        <img :src="current_user?.avatar" alt="hallo" class="w-12 h-12 rounded-full mr-5">-->
        <div class="leading-none">
          <h1 class="font-medium">{{ current_user?.name }}</h1>
          <p class="text-gray-500 text-sm">{{ current_user?.email }}</p>
        </div>
      </router-link>

      <button class="btn btn-lg btn-danger" @click="logout">Afmelden</button>
    </div>
    <div class="px-5 flex flex-col items-center justify-center flex-1">
      <!--
          New venue modal
      -->
      <transition name="modalfade">
        <modal v-if="showNewVenue" @close="showNewVenue = false">
          <div class="px-1 border-b border-gray-200 flex items-center justify-between">
            <p class="font-medium p-4">Nieuwe venue</p>
            <button class="p-4" @click="showNewVenue = false"><i class="far fa-times"></i></button>
          </div>
          <div class="p-5">
            <div class="p-4 rounded-md text-sm mb-5 flex items-center"
            :class="errorMessage ? 'bg-red-50 text-red-400' : 'bg-blue-50 text-blue-400' "
            >
              <i :class="errorMessage ? 'fa-exclamation' : 'fa-info-circle'" class="far mr-4"></i>
              <p v-if="!errorMessage">Registreer een nieuwe venue</p>
              <p v-else>{{errorMessage}}</p>
            </div>
            <div class="mb-4">
              <label>Naam <span class="required-star">*</span></label>
              <input v-model="name" type="text" class="input">
            </div>
            <div class="mb-4">
              <label>Subdomein <span class="required-star">*</span></label>
              <input v-model="subdomain" type="text" class="input">
            </div>
            <div class="mb-4 flex flex-col">
              <label for="plan">Abonnement <span class="required-star">*</span></label>
              <select name="plan" id="plan" v-model="plan_id">
                <option :value="plan.id" v-for="plan in plans" :selected="plan">{{ plan.name }}</option>
              </select>
            </div>
          </div>
          <div class="flex justify-end bg-gray-50 px-5 py-3">
            <button class="btn btn-primary ml-auto" @click="addVenue">
              Aanmaken
            </button>
          </div>
        </modal>
      </transition>


      <!--
          Payment modal
      -->
      <modal v-if="subscription_url && !showNewVenue">
        <div class="px-1 border-b border-gray-200 flex items-center justify-between h-full">
          <p class="font-medium p-4">Rond je betaling af</p>
          <button class="p-4" @click="showNewVenue = false"><i class="far fa-times"></i></button>
        </div>
        <div class="p-5">
          <div class="bg-red-50 text-red-400 p-4 rounded-md text-sm mb-5 flex items-center">
            <i class="fas fa-triangle-exclamation mr-4"></i>
            <p>Rond de betaling voor je abonnement af.</p>
          </div>
        </div>
        <div class="flex justify-end bg-gray-50 px-5 py-3">
          <button class="btn btn-secondary ml-auto" @click="openURL(subscription_url)">
            Naar betalen
          </button>
        </div>
      </modal>

      <!--
          Content
      -->

      <div @click="showNewVenue = true" class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="w-48 h-48 rounded-md shadow flex items-center justify-center text-center border-2 border-dashed border-indigo-500 cursor-pointer hover:shadow-lg hover:border-indigo-800">
          <div class="block text-sm font-semibold text-gray-900">
            <p class="text-3xl material-symbols-outlined">add</p>
            <p>Nieuwe</p>
            <p>Vestiging</p>
          </div>
        </div>

        <div v-for="user_venue in user_venues">
          <div class="w-48 h-48 rounded-md shadow flex flex-col gap-6 items-center justify-center text-center border-2 border-indigo-500 cursor-pointer hover:shadow-lg hover:border-indigo-800"
             v-if="user_venue.venue.stripe_current_period_ends_at != null && (new Date(user_venue.venue.stripe_current_period_ends_at) > new Date())" @click="current_venue = user_venue.venue;">
            <a class="text-sm font-semibold text-gray-900 flex justify-center items-center" :href="'/store/' + user_venue.venue.id + '/home'">
              <span class="mr-2">{{ user_venue.venue.name }}</span>
              <component :is="ArrowTopRightOnSquareIcon" class="text-gray-400 group-hover:text-indigo-600 h-6 w-6 shrink-0" aria-hidden="true"></component>
            </a>
<!--            <a :href="'/store/' + user_venue.venue.id + '/home'" class="btn btn-primary">Bezoeken</a>-->
            <button @click="openCustomerPortal" class="btn btn-danger">Abonnement</button>
          </div>
        </div>
      </div>

<!--      <div class="venue-wrap flex flex-wrap justify-center gap-4">-->
<!--        <loader v-if="loading"></loader>-->
<!--        <div v-for="user_venue in user_venues" class="bg-white">-->
<!--          <a v-if="user_venue.venue.stripe_current_period_ends_at != null && (new Date(user_venue.venue.stripe_current_period_ends_at) > new Date())" @click="current_venue = user_venue.venue;" :href="'/store/' + user_venue.venue.id + '/home'" class="cursor-pointer">-->
<!--            <img v-if="user_venue.venue.avatar" class="w-32 h-32 rounded-md bg-gray-100 shadow border-8 border-white object-contain" :src="user_venue.venue.avatar.full_path" :alt="user_venue.venue.name"/>-->
<!--            <p v-else class="w-32 h-32 rounded-md bg-gray-100 shadow border-8 border-white flex items-center justify-center text-center">{{ user_venue.venue.name }}</p>-->
<!--            <p>Beheren</p>-->
<!--          </a>-->

<!--          <div v-else class="cursor-not-allowed relative">-->
<!--            <img v-if="user_venue.venue.avatar" class="w-32 h-32 rounded-md bg-gray-100 shadow border-8 border-white object-contain" :src="user_venue.venue.avatar.full_path" :alt="user_venue.venue.name"/>-->
<!--            <p v-else class="w-32 h-32 rounded-md bg-gray-100 shadow border-8 border-white flex items-center justify-center text-center opacity-45"-->
<!--            >{{ user_venue.venue.name }}</p>-->
<!--            <div class="absolute top-1/3 left-3 -rotate-45 text-semibold text-3xl text-red-500">-->
<!--              Inactive-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="w-32 h-32 rounded-md bg-gray-100 text-gray-500 shadow border-8 border-white font-bold cursor-pointer flex items-center justify-center text-center" @click="showNewVenue = true">-->
<!--          <span class="text-3xl material-symbols-outlined">add</span>-->
<!--        </div>-->
<!--      </div>-->

    </div>
  </main>
</template>

<script>

import Loader from "./Components/Loader.vue";
import Modal from "./Components/Modal.vue";
import {DateTime} from "luxon";
import {ArrowTopRightOnSquareIcon} from "@heroicons/vue/24/outline/index.js";

export default {
  name: "VenueSelector",
  components: {Loader, Modal},
  data() {
    return {
      showNewVenue: false,
      user_venues: [],
      plans: [],

      errors: [],
      errorMessage: "",
      loading: false,
      current_user: null,
      subscription_url: null,

      name: "",
      subdomain: "",
      plan_id: null,
    }
  },

  methods: {
    ArrowTopRightOnSquareIcon,
    fetchUser() {
      axios.get('/users/current')
          .then(response => {
            this.current_user = response.data.data;
            this.fetchData();
          })
    },

    fetchData() {
      this.loading = true;

      axios.get('/users/' + this.current_user.id + '/user-venues?all=true')
          .then(response => {
            this.user_venues = response.data.data;
          })
          .catch(e => {
            console.log("ERROR", e)
          })
          .finally(() => {
            this.loading = false;
          })
    },

    addVenue() {
      axios.post('/venues', {
        name: this.name,
        subdomain: this.subdomain.toLowerCase(),
        plan_id: this.plan_id,
      })
          .then(response => {
            this.name = "";
            this.showNewVenue = false;
            this.subscription_url = response.data.data;
          })
          .catch(error => {
            this.errors = error.response.data.errors;
            this.errorMessage = error.response.data.message;
            console.log(this.errors);
          })
    },

    openURL(url) {
      window.location.href = url
    },

    fetchPlans() {
      axios.get('/plans/available')
          .then(response => {
            this.plans = response.data.data;
          })
    },

    logout() {
      axios.post('/sanctum/logout')
          .then(() => {
            window.localStorage.removeItem('tr_auth_token');
            window.location.href = '/';
          })
    },

    openCustomerPortal() {
      alert('goed');
    }
  },

  mounted() {
    this.fetchUser();
    this.fetchPlans();
  },

  computed: {
    DateTime() {
      return DateTime
    },
    current_venue: {
      get() {
        return this.$store.state.venue;
      },
      set (value) {
        this.$store.commit('setVenue', value);
      }
    },

    // current_user: {
    //   get() {
    //     return this.$store.state.user;
    //   }
    // }
  }
}
</script>