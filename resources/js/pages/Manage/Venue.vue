<template>
  <main v-if="current_user && venue">
    <div>
      <div class="mb-12 flex justify-between">
        <div>
          <div class="font-semibold text-lg">{{ venue.name }}</div>
          <div class="text-sm dark:text-slate-400">Beheer hier de informatie en abonnement van je vestiging.</div>
        </div>
      </div>
    </div>

    <SuccessAlert v-if="saved" @close="this.saved = false;">Je wijzigingen zijn opgeslagen</SuccessAlert>

    <div>
      <div class="">
        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">Vestiging informatie</h3>
        <p class="mt-1 text-sm leading-6 text-gray-500 dark:text-slate-400">Praktische informatie over je vestiging.</p>
      </div>
      <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium leading-6 text-gray-900">Zichtbare naam <span class="required-star">*</span></dt>
            <div>
              <input v-model="formData.name" type="text" name="name" id="name"
                     v-on:keyup.enter="postData"
                     :class="errors.name ? 'ring-red-300' : ''"
                     class="input"
                     aria-invalid="true" aria-describedby="name-error"/>
              <span v-if="errors.name" class="text-red-500 text-sm">{{errors.name[0]}}</span>
            </div>
          </div>
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium leading-6 text-gray-900">Subdomein <span class="required-star">*</span></dt>
            <div>
              <input v-model="formData.subdomain" type="text" name="subdomain" id="subdomain"
                     v-on:keyup.enter="postData"
                     :class="errors.subdomain ? 'ring-red-300' : ''"
                     placeholder="Alleen a-z, geen spaties of speciale tekens"
                     class="input"
                     aria-invalid="true" aria-describedby="name-error"/>
              <span v-if="errors.subdomain" class="text-red-500 text-sm">{{errors.subdomain[0]}}</span>
            </div>
          </div>
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium leading-6 text-gray-900">Huidig betaaltermijn t/m</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" v-if="venue.stripe_current_period_ends_at">{{ $filters.humanDateTime(venue.stripe_current_period_ends_at) }}</dd>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" v-else>
              <button @click="generatePaymentURL" class="btn btn-success">Rond je betaling af</button>
            </dd>
          </div>
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-2">
            <dt class="text-sm font-medium leading-6 text-gray-900">Huidig abonnement</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ venue.plan.name }}</dd>
          </div>
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-2">
            <dt class="text-sm font-medium leading-6 text-gray-900">Unit limiet</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ venue.plan.unit_limit }}</dd>
          </div>
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-2">
            <dt class="text-sm font-medium leading-6 text-gray-900">
              <p>Abonnement</p>
              <p class="text-gray-500">Beheer je abonnement & facturen.</p>
            </dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2">
              <button @click="openCustomerPortal" :class="customerPortalLoading ? 'btn-secondary' : 'btn-primary'" class="btn"><i v-if="customerPortalLoading" class="fa fa-spinner mr-2 animate-spin"></i> Beheer je abonnement</button>
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <div class="flex justify-end gap-4">
      <a href="/" class="btn btn-secondary">Terug</a>
      <button @click="postData" :class="loading ? 'btn btn-secondary opacity-50 cursor-not-allowed' : 'btn btn-primary'"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Opslaan</button>
    </div>
  </main>
</template>

<script>

import Loader from "../Components/Loader.vue";
import Modal from "../Components/Modals/Modal.vue";
import {DateTime} from "luxon";
import {ArrowTopRightOnSquareIcon} from "@heroicons/vue/24/outline/index.js";
import MultipleSelectUnits from "../Components/MultipleSelectUnits.vue";
import CurrencyInput from "../Components/CurrencyInput.vue";
import SuccessAlert from "../Components/Alerts/SuccessAlert.vue";

export default {
  name: "Venue",
  components: {SuccessAlert, CurrencyInput, MultipleSelectUnits, Loader, Modal},
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

    generatePaymentURL() {
      axios.post('/venues/' + this.venue_id + '/paymentlink')
          .then(response => {
            this.openURL(response.data.data);
          })
    },

    postData() {
      if(this.loading) return;
      this.loading = true;

      axios.put('/users/' + this.current_user.id + '/venue/' + this.venue_id, this.formData)
          .then(response => {
            this.venue = response.data.data;
            this.saved = true;
            this.errors = [];
          })
          .catch(e => {
            this.saved = false;
            this.errors = e.response.data.errors;
          })
          .finally(() => {
            this.loading = false;
          })
    },

    openCustomerPortal() {
      if(this.customerPortalLoading) return;
      this.customerPortalLoading = true;
      axios.post('/users/' + this.current_user.id + '/venue/' + this.venue_id + '/portal', {
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