<template>
  <div v-if="venue">

    <div class="mt-12">
      <div class="text-xl font-semibold">{{ venue.name }}</div>
    </div>

    <div class="bg-white p-12 mt-12 rounded-t-md space-y-12">
      <div>
        <h1 class="font-semibold border-b-2 pb-2 mb-4">1. Op welke dag wil je reserveren?</h1>
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

      <div v-if="date">
        <h1 class='font-semibold border-b-2 pb-2 mb-4'>2. Welke tijd(en) wil je reserveren?</h1>
        <div v-if="!date" class="text-center text-lg py-5">Selecteer eerst een datum om verder te gaan.</div>
        <div v-else>
          <div v-if="units.length <= 0" class="text-center text-lg pt-6">Er is op deze datum (nog) niks beschikbaar. Probeer een andere datum</div>
          <div v-else v-for="unit in units" class="rounded-lg lg:p-4">

            <div class="font-semibold text-md">{{ unit.unit.name }}</div>
            <div class="text-xs text-gray-700 mb-4 font-semibold">{{ $filters.currency(unit.price) }} per {{ unit.interval }} minuten</div>
            <div v-if="unit.timeblocks.length <= 0">Deze unit heeft geen tijden beschikbaar voor deze dag</div>
            <div v-else class="inline-block">
              <button-time-reservation
                v-if="unit.timeblocks"
                v-for="timeblock in unit.timeblocks"
                :key="timeblock"
                :timeblock="timeblock"
                :unit="unit"
                :selected="selected"
                @updateList="updateSelectedTimeblocks"
              />
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div v-else>
    Pagina niet gevonden
  </div>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import 'flatpickr/dist/flatpickr.css';
import {CalendarDaysIcon} from "@heroicons/vue/24/outline/index.js";
import flatpickr from "flatpickr";
import {DateTime} from "luxon";
import ButtonTimeReservation from "./Components/ButtonTimeReservation.vue";

export default {
  name: "Home",
  components: {ButtonTimeReservation, flatPickr},
  data() {
    return {
      key: 'value',
      venue: null,

      date: null,
      year: null,
      week: null,
      day: null,
      selected: [],

      units: [],

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
      }
    }
  },

  methods: {
    CalendarDaysIcon,
    fetchVenue() {
      axios.get('/venue/' + this.subdomain)
          .then(response => {
            this.venue = response.data.data;
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
            this.units = response.data.data;
          })
    },

    updateSelectedTimeblocks(list) {
      this.selected = list;
    },

    openPickr() {
      const fp = flatpickr('#flatpickr', {});
      fp.open();
    },
  },

  mounted() {
    this.fetchVenue();
  },

  computed: {
    subdomain: function() {
      return window.location.hostname.split('.')[0]
    }
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