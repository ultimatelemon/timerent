<template>
  <main v-if="current_user && venue">
    <div>
      <div class="mb-12 flex justify-between">
        <div>
          <div class="font-semibold text-lg">{{ venue.name }}</div>
          <div class="text-sm">Beheer hier de informatie en abonnement van je vestiging.</div>
        </div>
      </div>
    </div>

<!--    <div class="rounded-md bg-green-50 p-4 mb-12">-->
<!--      <div class="flex">-->
<!--        <div class="flex-shrink-0">-->
<!--          <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">-->
<!--            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />-->
<!--          </svg>-->
<!--        </div>-->
<!--        <div class="ml-3">-->
<!--          <h3 class="text-sm font-medium text-green-800">Je wijzigingen zijn opgeslagen</h3>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

    <SuccessAlert v-if="saved" @close="this.saved = false;">Je wijzigingen zijn opgeslagen</SuccessAlert>

    <div>
      <div class="">
        <h3 class="text-base font-semibold leading-7 text-gray-900">Vestiging informatie</h3>
        <p class="mt-1 text-sm leading-6 text-gray-500">Praktische informatie over je vestiging.</p>
      </div>
      <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium leading-6 text-gray-900">Zichtbare naam <span class="required-star">*</span></dt>
            <input v-model="formData.subdomain" type="text" name="subdomain" id="subdomain"
                   v-on:keyup.enter="postData"
                   :class="errors.subdomain ? 'ring-red-300' : ''"
                   class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                   aria-invalid="true" aria-describedby="name-error"/>
          </div>
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium leading-6 text-gray-900">Subdomein <span class="required-star">*</span></dt>
            <input v-model="formData.subdomain" type="text" name="subdomain" id="subdomain"
                   v-on:keyup.enter="postData"
                   :class="errors.subdomain ? 'ring-red-300' : ''"
                   placeholder="Alleen a-z, geen spaties of speciale tekens"
                   class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                   aria-invalid="true" aria-describedby="name-error"/>
          </div>
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium leading-6 text-gray-900">Huidig betaaltermijn t/m</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $filters.humanDateTime(venue.stripe_current_period_ends_at) }}</dd>
          </div>
          <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-2">
            <dt class="text-sm font-medium leading-6 text-gray-900">Huidig abonnement</dt>

            {{venue}}
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

    postData() {
      if(this.loading) return;
      this.loading = true;

      axios.put('/users/' + this.current_user.id + '/venue/' + this.venue_id, this.formData)
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