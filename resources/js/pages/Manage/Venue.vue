<template>
  <main class="fixed top-0 left-0 right-0 bottom-0 bg-gray-100 flex flex-col">
    <div v-if="current_user" class="leading-tight flex items-center p-5 rounded border-b mb-12">
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
      <div class="pb-8">
        <div class="font-semibold text-lg">Beheer</div>
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

<!--        <div>-->
<!--          <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Omschrijving</label>-->
<!--          <div class="relative mt-2 rounded-md shadow-sm">-->
<!--            <input v-model="formData.description" type="text" name="description" id="description"-->
<!--                   v-on:keyup.enter="postData"-->
<!--                   :class="errors.description ? 'ring-red-300' : ''"-->
<!--                   class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"-->
<!--                   aria-invalid="true" aria-describedby="description-error"/>-->
<!--            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">-->
<!--              <svg v-if="errors.description" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">-->
<!--                <path fill-rule="evenodd"-->
<!--                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"-->
<!--                      clip-rule="evenodd"/>-->
<!--              </svg>-->
<!--            </div>-->
<!--          </div>-->
<!--          <p v-if="errors.description" class="mt-2 text-sm text-red-600" id="description-error">{{ errors.description[0] }}</p>-->
<!--        </div>-->

<!--        <div>-->
<!--          <label for="tax_percentage" class="block text-sm font-medium leading-6 text-gray-900">Belasting tarief <span class="required-star">*</span></label>-->
<!--          <select v-model="formData.tax_percentage" id="location" name="location" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">-->
<!--            <option :value="0">0%</option>-->
<!--            <option :value="9">9%</option>-->
<!--            <option :value="21">21%</option>-->
<!--          </select>-->
<!--        </div>-->

<!--        <div>-->
<!--          <label for="price_per_timeblock" class="block text-sm font-medium leading-6 text-gray-900">Prijs per <span :class="formData.price_per_timeblock ? '' : 'font-semibold'">reservering</span>/<span :class="formData.price_per_timeblock ? 'font-semibold' : ''">tijdblock</span></label>-->
<!--          <div class="relative mt-2">-->
<!--            <Switch v-model="formData.price_per_timeblock" :class="[formData.price_per_timeblock ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">-->
<!--              <span class="sr-only">Use setting</span>-->
<!--              <span :class="[formData.price_per_timeblock ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']">-->
<!--            <span :class="[formData.price_per_timeblock ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">-->
<!--              <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">-->
<!--               <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />-->
<!--              </svg>-->
<!--             </span>-->
<!--            <span :class="[formData.price_per_timeblock ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">-->
<!--             <svg class="h-3 w-3 text-indigo-600" fill="currentColor" viewBox="0 0 12 12">-->
<!--               <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />-->
<!--             </svg>-->
<!--            </span>-->
<!--            </span>-->
<!--            </Switch>-->
<!--            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">-->
<!--              <svg v-if="errors.price_per_timeblock" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">-->
<!--                <path fill-rule="evenodd"-->
<!--                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"-->
<!--                      clip-rule="evenodd"/>-->
<!--              </svg>-->
<!--            </div>-->
<!--          </div>-->
<!--          <p v-if="errors.price_per_timeblock" class="mt-2 text-sm text-red-600" id="description-error">{{ errors.price_per_timeblock[0] }}</p>-->
<!--        </div>-->

<!--        <div>-->
<!--          <label for="max_per_day" class="block text-sm font-medium leading-6 text-gray-900">Max aantal per dag te reserveren (0 voor geen limiet) <span-->
<!--              class="required-star">*</span></label>-->
<!--          <div class="relative mt-2 rounded-md shadow-sm">-->
<!--            <input v-model="formData.max_per_day" type="number" name="max_per_day" id="max_per_day"-->
<!--                   v-on:keyup.enter="postData"-->
<!--                   :class="errors.max_per_day ? 'ring-red-300' : ''"-->
<!--                   class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"-->
<!--                   aria-invalid="true" aria-describedby="name-error"/>-->
<!--            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">-->
<!--              <svg v-if="errors.max_per_day" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">-->
<!--                <path fill-rule="evenodd"-->
<!--                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"-->
<!--                      clip-rule="evenodd"/>-->
<!--              </svg>-->
<!--            </div>-->
<!--          </div>-->
<!--          <p v-if="errors.max_per_day" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.max_per_day[0] }}</p>-->
<!--        </div>-->

<!--        <div>-->
<!--          <label for="price" class="block text-sm font-medium leading-6 text-gray-900">Prijs per {{ formData.price_per_timeblock ? 'tijdblock' : 'reservering' }}</label>-->
<!--          <div class="relative mt-2">-->
<!--            <CurrencyInput v-on:keyup.enter='postData' v-model='formData.price'></CurrencyInput>-->
<!--            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">-->
<!--              <svg v-if="errors.price" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">-->
<!--                <path fill-rule="evenodd"-->
<!--                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"-->
<!--                      clip-rule="evenodd"/>-->
<!--              </svg>-->
<!--            </div>-->
<!--          </div>-->
<!--          <p v-if="errors.price" class="mt-2 text-sm text-red-600" id="description-error">{{ errors.price[0] }}</p>-->
<!--        </div>-->

<!--        <div>-->
<!--          <label for="is_active" class="block text-sm font-medium leading-6 text-gray-900">Actief</label>-->
<!--          <div class="relative mt-2">-->
<!--            <Switch v-model="formData.is_active" :class="[formData.is_active ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">-->
<!--              <span class="sr-only">Use setting</span>-->
<!--              <span :class="[formData.is_active ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']">-->
<!--            <span :class="[formData.is_active ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">-->
<!--              <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">-->
<!--               <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />-->
<!--              </svg>-->
<!--             </span>-->
<!--            <span :class="[formData.is_active ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">-->
<!--             <svg class="h-3 w-3 text-indigo-600" fill="currentColor" viewBox="0 0 12 12">-->
<!--               <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />-->
<!--             </svg>-->
<!--            </span>-->
<!--            </span>-->
<!--            </Switch>-->
<!--            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">-->
<!--              <svg v-if="errors.is_active" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">-->
<!--                <path fill-rule="evenodd"-->
<!--                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"-->
<!--                      clip-rule="evenodd"/>-->
<!--              </svg>-->
<!--            </div>-->
<!--          </div>-->
<!--          <p v-if="errors.is_active" class="mt-2 text-sm text-red-600" id="description-error">{{ errors.is_active[0] }}</p>-->
<!--        </div>-->

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
      current_user: null,
      errors: [],

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
      this.loading = true;

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
            this.loading = false;
          })
    },

    postData() {
      //
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