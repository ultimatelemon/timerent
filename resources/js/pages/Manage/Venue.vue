<template>
  <main class="fixed top-0 left-0 right-0 bottom-0 bg-gray-100 flex flex-col" v-if="current_user && venue">
    <div class="leading-tight flex items-center p-5 rounded border-b mb-12">
      <router-link to="#" class="cursor-pointer flex items-center mr-auto">
        <!--        <img :src="current_user?.avatar" alt="hallo" class="w-12 h-12 rounded-full mr-5">-->
        <div class="leading-none">
          <h1 class="font-medium">{{ current_user?.name }}</h1>
          <p class="text-gray-500 text-sm">{{ current_user?.email }}</p>
        </div>
      </router-link>

      <button class="btn btn-lg btn-danger" @click="logout">Afmelden</button>
    </div>
    <div class="px-5">
      <div v-if="saved" class="my-3 alert alert-success">
        <div class="flex justify-between items-center">
          <div>Je instellingen zijn opgeslagen.</div>
          <div @click="saved = false" class="cursor-pointer"><i class="fa fa-close"></i></div>
        </div>
      </div>
      <div class="pb-8">
        <div class="font-semibold text-lg">Informatie</div>
        <div class="text-sm">Beheer hier je venue informatie en instellingen</div>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
        <div>
          <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Naam <span
              class="required-star">*</span></label>
          <div class="relative mt-2 rounded-md shadow-sm">
            <input v-model="formData.name" type="text" name="name" id="name"
                   v-on:keyup.enter="postData"
                   :class="errors.name ? 'ring-red-300' : ''"
                   class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                   aria-invalid="true" aria-describedby="name-error"/>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
              <svg v-if="errors.name" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                      clip-rule="evenodd"/>
              </svg>
            </div>
          </div>
          <p v-if="errors.name" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.name[0] }}</p>
        </div>

        <div>
          <label for="subdomain" class="block text-sm font-medium leading-6 text-gray-900">Subdomein <span
              class="required-star">*</span></label>
          <div class="relative mt-2 rounded-md shadow-sm">
            <input v-model="formData.subdomain" type="text" name="subdomain" id="subdomain"
                   v-on:keyup.enter="postData"
                   :class="errors.subdomain ? 'ring-red-300' : ''"
                   class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                   aria-invalid="true" aria-describedby="name-error"/>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
              <svg v-if="errors.subdomain" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                      clip-rule="evenodd"/>
              </svg>
            </div>
          </div>
          <p v-if="errors.subdomain" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.subdomain[0] }}</p>
        </div>
      </div>

      <div class="pb-8">
        <div class="font-semibold text-lg">Abonnement</div>
        <div class="text-sm">Bekijk hier je huidige abonnement. Aanpassingen kun je direct in je Stripe dashboard regelen.</div>
      </div>

      <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
          <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Huidige pakket</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ venue.plan.name }}</dd>
          </div>
          <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Huidig abonnement termijn tot</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $filters.humanDateTime(venue.stripe_current_period_ends_at) }}</dd>
          </div>
          <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Maximale units</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ venue.plan.unit_limit }}</dd>
          </div>
          <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Salary expectation</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">$120,000</dd>
          </div>
          <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Beheer je abonnement & bekijk je facturen</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
              <button @click="openCustomerPortal" :class="customerPortalLoading ? 'btn btn-secondary opacity-50 cursor-not-allowed' : 'btn btn-primary'"><i v-if="customerPortalLoading" class="fa fa-spinner mr-2 animate-spin"></i> Open portal</button>
            </dd>
          </div>
        </dl>
      </div>
      <div class="flex justify-end gap-4">
        <a href="/" class="btn btn-secondary">Terug</a>
        <button @click="postData" :class="loading ? 'btn btn-secondary opacity-50 cursor-not-allowed' : 'btn btn-primary'"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Opslaan</button>
      </div>
    </div>
  </main>
</template>

<script>

import Loader from "../Components/Loader.vue";
import Modal from "../Components/Modal.vue";
import {DateTime} from "luxon";
import {ArrowTopRightOnSquareIcon} from "@heroicons/vue/24/outline/index.js";
import MultipleSelectUnits from "../Components/MultipleSelectUnits.vue";
import CurrencyInput from "../Components/CurrencyInput.vue";

export default {
  name: "Venue",
  components: {CurrencyInput, MultipleSelectUnits, Loader, Modal},
  data() {
    return {
      venue_id: this.$route.params.venue,
      venue: null,
      current_user: null,
      errors: [],

      loading: false,
      customerPortalLoading: false,
      saved: false,

      formData: {
        name: null,
        subdomain: null,
      }
    }
  },

  methods: {
    ArrowTopRightOnSquareIcon,
    fetchUser() {
      axios.get('/users/current')
          .then(response => {
            this.current_user = response.data.data;
            this.current_user_role = response.data.role;
            this.fetchData();
          })
    },

    fetchData() {
      axios.get('/users/' + this.current_user.id + '/venue/' + this.venue_id)
          .then(response => {
            this.venue = response.data.data;
            this.formData.name = this.venue.name;
            this.formData.subdomain = this.venue.subdomain;
          })
          .catch(e => {
            console.log("ERROR", e)
          })
          .finally(() => {

          })
    },

    postData() {
      if(this.loading) return;
      this.loading = true;

      axios.put('/users/' + this.current_user.id + '/venue/' + this.$store.state.venue.id, this.formData)
          .then(response => {
            this.venue = response.data.data;
            this.saved = true;
          })
          .catch(e => {
            this.saved = false;
            this.errors = e.response.data.errors;
          })
          .finally(() => {
            this.loading = false;
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
      if(this.customerPortalLoading) return;
      this.customerPortalLoading = true;
      axios.post('/users/' + this.current_user.id + '/venue/' + this.$store.state.venue.id + '/portal', {
        return_url: window.location.href,
      })
          .then(response => {
            console.log(response.data.data);
            this.openURL(response.data.data.url);
          })
          .finally(() => {
            this.customerPortalLoading = false;
          })
    },

    openURL(url) {
      window.location.href = url
    },
  },

  mounted() {
    this.fetchUser();
  },

  computed: {
    DateTime() {
      return DateTime
    },
    current_venue: {
      get() {
        return this.$store.state.venue;
      },
      set(value) {
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