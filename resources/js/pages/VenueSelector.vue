<template>
  <main v-if="current_user">
    <div>
      <div class="mb-12 flex justify-between">
        <div>
          <div class="font-semibold text-lg">{{ this.greeting }}, {{current_user.name}}</div>
          <div class="text-sm">Beheer hier je persoonlijke account, maak een nieuwe vestiging of navigeer direct naar een bestaande vestiging.</div>
        </div>
      </div>
    </div>

    <div>
      <h1 class="mb-2 font-semibold">Vestigingen</h1>
      <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4">
        <button @click="showNewVenue = true;" type="button" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m0-4c0 4.418-7.163 8-16 8S8 28.418 8 24m32 10v6m0 0v6m0-6h6m-6 0h-6" />
          </svg>
          <span class="mt-2 block text-sm font-semibold text-gray-900">Nieuwe vestiging</span>
        </button>

        <a @click="current_venue = user_venue.venue;" :href="'/store/' + user_venue.venue.id + '/home'" v-for="user_venue in user_venues" type="button" class="relative block w-full rounded-lg border-2 border-gray-300 p-12 text-center hover:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
<!--          <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">-->
<!--            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m0-4c0 4.418-7.163 8-16 8S8 28.418 8 24m32 10v6m0 0v6m0-6h6m-6 0h-6" />-->
<!--          </svg>-->
          <i v-if="user_venue.venue.stripe_current_period_ends_at != null && (new Date(user_venue.venue.stripe_current_period_ends_at) > new Date())" class="text-4xl mx-auto far fa-arrow-up-right-from-square font-normal"></i>
          <span v-else class="text-red-500 text-sm">
              {{ (user_venue.venue.stripe_current_period_ends_at ? 'Verlopen op ' +  $filters.humanDate(user_venue.venue.stripe_current_period_ends_at) : 'Betaling (nog) niet afgerond') }}
            </span>
          <span class="mt-2 block text-sm font-semibold text-gray-900">{{ user_venue.venue.name }}</span>
          <a :href="'/manage/' + user_venue.venue.id" class="btn btn-primary mt-4">Beheer vestiging</a>
        </a>

      </div>
    </div>

    <transition name="modalfade" class="">
      <modal v-if="showNewVenue" @close="showNewVenue = false">
        <div class="p-4">
          <div class="mb-6 flex justify-between">
            <div>
              <div class="font-semibold text-lg">Nieuwe vestiging</div>
              <div class="text-sm">Laten we samenwerken! Registreer hier jouw nieuwe vestiging.</div>
            </div>
          </div>
          <div>
            <div class="mb-4">
              <label>Naam <span class="required-star">*</span></label>
              <input v-model="name" type="text" class="input" placeholder="Wat is de naam van je vestiging?">
              <span v-if="errors.name" class="text-red-500 text-sm">{{errors.name[0]}}</span>
            </div>

            <div class="mb-4">
              <label>Subdomein <span class="required-star">*</span></label>
              <input v-model="subdomain" type="text" class="input" placeholder="Alleen a-z, geen spaties of speciale tekens">
              <span v-if="errors.subdomain" class="text-red-500 text-sm">{{errors.subdomain[0]}}</span>
            </div>

            <div class="mb-4 flex flex-col">
              <label for="plan">Abonnement <span class="required-star">*</span></label>
              <select name="plan" id="plan" v-model="plan_id">
                <option :value="null" :disabled="true">Kies een abonnement</option>
                <option :value="plan.id" v-for="plan in plans" :selected="plan">{{ plan.name  }}</option>
              </select>
              <span v-if="errors.plan_id" class="text-red-500 text-sm">{{errors.plan_id[0]}}</span>
            </div>

            <div class="flex justify-end px-5 py-3">
              <button :class="loading ? 'btn-secondary' : 'btn-primary'" class="btn ml-auto" @click="addVenue"><i v-if="loading" class="fa fa-spinner animate-spin mr-2"></i> Aanmaken</button>
            </div>
          </div>
        </div>
      </modal>
    </transition>

    <modal v-if="!showNewVenue && subscription_url" class="max-w-full">
      <div class="p-4 w-full">
        <div class="mb-6 flex justify-between">
          <div>
            <div class="font-semibold text-lg">Rond je betaling af</div>
            <div class="text-sm">Zodra je betaling is verwerkt gaat je abonnement in. Eventuele kortingscodes kun je hierna invullen.</div>
            <div class="flex justify-end mt-6 w-full">
<!--              <button class="btn btn-secondary" @click="openURL(subscription_url)">Annuleren</button>-->
              <button class="btn btn-primary" @click="openURL(subscription_url)">Naar betalen</button>
            </div>
          </div>
        </div>
      </div>
    </modal>

  </main>
</template>


<script>

import Loader from "./Components/Loader.vue";
import Modal from "./Components/Modals/Modal.vue";
import {DateTime} from "luxon";
import {ArrowTopRightOnSquareIcon, HomeIcon, UsersIcon} from "@heroicons/vue/24/outline/index.js";
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import {
  Bars3Icon,
  BellIcon,
  XMarkIcon,
  ChevronRightIcon,
  FolderIcon,
  CalendarIcon,
  DocumentDuplicateIcon, ChartPieIcon
} from '@heroicons/vue/24/outline';

export default {
  name: "VenueSelector",
  components: {DisclosurePanel, Disclosure, DisclosureButton, MenuButton, MenuItem, MenuItems, Loader, Modal, Bars3Icon, BellIcon, XMarkIcon, ChevronRightIcon},
  data() {
    return {

      showNewVenue: false,
      user_venues: [],
      plans: [],

      errors: [],
      errorMessage: "",
      loading: false,
      current_user: null,
      current_user_role: null,
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
            this.current_user_role = response.data.role;
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
      if(this.loading) return;

      this.loading = true;
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
          .finally(() => {
            this.loading = false;
          })
    },

    storeVenue() {
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
  },

  mounted() {
    this.fetchUser();
    this.fetchPlans();
  },

  computed: {
    DateTime() {
      return DateTime
    },
    greeting() {
      const hour = DateTime.now().hour;
      if (hour >= 0 && hour < 6) return 'Goedenacht';
      if (hour >= 6 && hour < 12) return 'Goedemorgen';
      if (hour >= 12 && hour < 18) return 'Goedemiddag';
      if (hour >= 18 && hour < 24) return 'Goedenavond';
      return hour;
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