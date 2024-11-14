<template>
  <div v-if="venue && (new Date(venue.stripe_current_period_ends_at) > new Date())">

    <TopBar></TopBar>

    <div class="bg-white p-12 mt-12 rounded-t-md space-y-12">
      <div>
        <h1 class="font-semibold border-b-4 pb-2 mb-4">1. Op welke dag wil je reserveren?</h1>
        <div>
          <div class="flex gap-2">
            <flat-pickr
                id="flatpickr"
                v-model="date"
                :config="config"
                class="input cursor-pointer"
                :placeholder="date ?? 'Selecteer een datum'"
            />
            <div @click="openPickr" class="input-icon"><component :is="CalendarDaysIcon" class="h-5 w-5"></component></div>
          </div>
        </div>
      </div>
      <div>
        <h1 class='font-semibold border-b-4 pb-2 mb-4'>2. Welke tijd(en) wil je reserveren?</h1>
        <div v-if="!date" class="text-center text-lg py-5">Selecteer eerst een datum om verder te gaan.</div>
        <div v-else>
          <div v-if="units.length <= 0" class="text-center text-lg pt-6">Er is op deze datum (nog) niks beschikbaar. Probeer een andere datum</div>
          <div v-else class="">
            <div v-for="unit in units">
              <div class="lg:p-4">
                <div class="font-semibold text-md mb-1">{{ unit.unit.name }} <span class="text-xs text-gray-700 font-semibold">- {{ unit.unit.description ?? 'Geen omschrijving' }}</span></div>
                <div v-if="unit.unit.groups.length > 0" class="text-xs text-red-700 font-semibold">Alleen te reserveren voor groep(en): {{ unit.unit.groups.map(g => g.name).join(', ') }}</div>
                <div class="text-xs text-gray-700 mb-4 font-semibold">{{ $filters.currency(unit.price) }} per {{ unit.interval }} minuten</div>
                <div v-if="unit.timeblocks.length <= 0">Deze unit heeft geen tijden beschikbaar voor deze dag</div>
                <div v-else class="inline-block">
                  <button-time-reservation
                      v-if="unit.timeblocks"
                      v-for="timeblock in unit.timeblocks"
                      :key="timeblock"
                      :timeblock="timeblock"
                      :unit="unit.unit"
                      :selected="selected"
                      @updateList="updateSelectedTimeblocks"
                  />
                </div>
                <hr class="my-2">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white">
        <h1 class="font-semibold border-b-4 pb-2 mb-4">3. Wil je ook producten bijboeken tijdens je reservering?</h1>
        <div v-if="products.length === 0" class="text-center text-lg py-5">Er zijn geen producten beschikbaar</div>
        <div v-else class="divide-y">
          <div v-for="product in products" class="grid grid-cols-2 lg:grid-cols-3 items-center w-full py-4">
            <div class="col-span-3 font-semibold">{{ product.name }} <p class="text-sm font-normal text-red-500 pb-4" v-if="product.units.length > 0">- Let op: dit product is alleen te boeken bij unit(s): <span class="font-semibold">{{product.units.map(u => u.name).join(', ')}}</span></p></div>
            <div>
              <div class="text-sm">{{ product.description ?? "Dit product heeft (nog) geen omschrijving" }}</div>
            </div>
            <div class="text-center">{{ $filters.currency(product.price) }}</div>
            <div class="text-center">
              <input class="checkbox" type="checkbox" @change="toggleProduct(product.id)">
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white">
        <h1 class="font-semibold border-b-4 pb-2 mb-8">4. Maak je reservering compleet</h1>
        <div class="space-y-8">
          <div>
            <label for='name'>Volledige naam <span class='text-red-500'>*</span></label>
            <div class='mt-2'>
              <input v-model='name' type='text' name='name' autocomplete='name'
                     placeholder="Jan Petersen"
                     :class='errors?.name ? "ring-red-500" : ""'
                     class='block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'>
              <span class='text-red-500' v-if='errors?.name'>{{ errors?.name[0] }}</span>
            </div>
          </div>

          <div>
            <label for='email'>Email <span class='text-red-500'>*</span></label>
            <div class='mt-2'>
              <input v-model='email' type='text' name='email' autocomplete='email'
                     placeholder="j.petersen@voorbeeld.com"
                     :class='errors?.email ? "ring-red-500" : ""'
                     class='block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'>
              <span class='text-red-500' v-if='errors?.email'>{{ errors?.email[0] }}</span>
            </div>
          </div>

          <div>
            <label for='phone_number'>Telefoonnummer <span class='text-red-500'>*</span></label>
            <div class='mt-2'>
              <input v-model='phone_number' type='text' name='phone_number' autocomplete='phone_number'
                     placeholder="0612345678"
                     :class='errors?.phone_number ? "ring-red-500" : ""'
                     class='block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'>
              <span class='text-red-500' v-if='errors?.phone_number'>{{ errors?.phone_number[0] }}</span>
            </div>
          </div>
        </div>

        <p v-if="errors.error" class="mt-8 text-red-500">Foutmelding: {{errors.error}}</p>

        <div class="mt-8 flex justify-end">
          <button @click="postData" class="btn btn-lg" :class="(this.loading || this.selected.length === 0 || this.name === '' || this.email === '' || this.phone_number === '' ? 'btn-secondary opacity-50 cursor-not-allowed' : 'btn-primary')"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Afronden</button>
        </div>

      </div>

    </div>
    <div class='pb-6 w-full text-sm text-gray-600 text-center mt-16'>
      <p>Algemene voorwaarden en het privacybeleid van timerent.nl zijn van toepassing.</p>
      <p>&copy;Timerent BV - Part of <a href="https://ultimateLemon.eu" target="_blank">UltimateLemon</a></p>
    </div>

    <div v-if='showModal' class='relative z-50' aria-labelledby='modal-title' role='dialog' aria-modal='true'>
      <div class='fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity'></div>

      <div class='fixed inset-0 z-10 overflow-y-auto'>
        <div class='flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0'>
          <div
              class='relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6'>
            <div class='sm:flex sm:items-start'>
              <div
                  class='mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10'>
                <svg class='h-6 w-6 text-red-600' fill='none' viewBox='0 0 24 24' stroke-width='1.5'
                     stroke='currentColor' aria-hidden='true'>
                  <path stroke-linecap='round' stroke-linejoin='round'
                        d='M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' />
                </svg>
              </div>
              <div class='mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left'>
                <h3 class='text-base font-semibold leading-6 text-gray-900' id='modal-title'>Rond je reservering af</h3>
                <div class='mt-2'>
                  <p class='text-sm text-gray-500'>Letop, betaalpagina opent in een nieuw tabblad.</p>
                </div>
              </div>
            </div>
            <div class='mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-4'>
              <a :href='url' target='blank' type='button' class='btn btn-primary'>Betalen</a>
              <button @click='showModal = !showModal' type='button'
                      class='mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto'>
                Sluiten
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div v-else class="h-full flex items-center justify-center">
    <PageNotAvailable></PageNotAvailable>
  </div>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import 'flatpickr/dist/flatpickr.css';
import {CalendarDaysIcon} from "@heroicons/vue/24/outline/index.js";
import flatpickr from "flatpickr";
import {DateTime} from "luxon";
import ButtonTimeReservation from "./Components/ButtonTimeReservation.vue";
import TopBar from "./Components/TopBar.vue";
import PageNotAvailable from "./PageNotAvailable.vue";

export default {
  name: "Home",
  components: {PageNotAvailable, TopBar, ButtonTimeReservation, flatPickr},
  data() {
    return {
      venue: null,
      loading: false,
      showModal: false,
      url: "",
      current_member: null,

      date: null,
      year: null,
      week: null,
      day: null,
      name: "",
      email: "",
      phone_number: "",
      selected: [],
      selectedProducts: [],
      errors: [],

      units: [],
      products: [],

      config: {
        altFormat: 'M j, Y',
        altInput: true,
        dateFormat: 'd-m-Y',
        inline: false,
        showMonths: 1,
        monthSelectorType: 'static',
        locale: {
          firstDayOfWeek: 1
        },
      },
    }
  },

  methods: {
    CalendarDaysIcon,
    fetchVenue() {
      axios.get('/venue/' + this.subdomain)
          .then(response => {
            this.venue = response.data.data;
            if(new Date(response.data.data.stripe_current_period_ends_at) <= new Date()) this.activeSubscription = false;
            this.fetchProducts();
            this.fetchMemberData();
            console.log('fetch venue');
          })
    },

    fetchMemberData() {
      if(!window.localStorage.getItem('tr_member_auth_token')) return;

      axios.get('/app/members/current')
          .then(response => {
            if(response.data.data) {
              this.current_member = response.data.data;
              this.name = response.data.data.name;
              this.email = response.data.data.email;
              this.phone_number = response.data.data.phone_number;
            }
          })
    },

    fetchUnits() {
      axios.get('/venue/' + this.venue.id + '/units', {
        params: {
          year: this.year,
          week: this.week,
          day: this.day,
          date: this.date,
        }
      })
          .then(response => {
            // this.units = response.data.data;
            this.units = response.data.data.filter(unit => this.canViewUnit(unit));
          })
    },

    canViewUnit(unit) {
      if(unit.unit.groups.length === 0) return true;
      if(!this.current_member || !this.current_member.group.id) return false;
      let groupIds = unit.unit.groups.map(g => g.id);
      return groupIds.includes(this.current_member.group.id);
    },

    fetchProducts() {
      axios.get('/venue/' + this.venue.id + '/products')
          .then(response => {
            this.products = response.data.data;
          })
    },

    updateSelectedTimeblocks(list) {
      this.selected = list;
    },

    openPickr() {
      const fp = flatpickr('#flatpickr', {});
      fp.open();
    },

    toggleProduct(id) {
      if (this.selectedProducts.includes(id)) {
        return this.selectedProducts = this.selectedProducts.filter(e => e !== id);
      }
      return this.selectedProducts.push(id);
    },

    postData() {
      if(this.loading || this.selected.length === 0 || this.name === '' || this.email === '' || this.phone_number === '') return;

      this.loading = true;
      this.errors = [];

      axios.post('/reservations/store', {
        subdomain: this.subdomain,
        name: this.name,
        email: this.email,
        phone_number: this.phone_number,
        date: this.date,
        comments: this.comments ?? 'Geen',
        products: this.selectedProducts,
        timeblocks: this.selected,
      })
          .then(response => {
            this.url = response.data.data;
            this.showModal = true;
            this.selected = [];
            this.fetchUnits()
          })
          .catch(e => {
            this.errors = e.response.data.errors;
            this.loading = false;
          })
          .finally(() => {
            this.loading = false;
          })
    }
  },

  mounted() {
    this.fetchVenue();
    // this.fetchProducts();
  },

  computed: {
    subdomain: function() {
      return window.location.hostname.split('.')[0]
    },
  },

  watch: {
    date: function() {
      this.year = DateTime.fromFormat(this.date, 'dd-MM-yyyy').year;
      this.week = DateTime.fromFormat(this.date, 'dd-MM-yyyy').weekNumber;
      this.day = (DateTime.fromFormat(this.date, 'dd-MM-yyyy').weekday);
      this.selected = [];
      this.fetchUnits();
    }
  }
}
</script>