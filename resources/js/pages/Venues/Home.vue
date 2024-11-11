<template>
  <div>
    <h1 class="font-semibold text-xl">{{ greeting }}</h1>
    <h3 class="text-base leading-6 text-gray-900">Bekijk hier een overzicht van deze week t.o.v. vorige week</h3>

    <div class="mt-6">

      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 mt-5">
        <div class="bg-gray-100 rounded-md h-32 relative overflow-hidden"><div class="load-activity"></div></div>
        <div class="bg-gray-100 rounded-md h-32 relative overflow-hidden"><div class="load-activity"></div></div>
        <div class="bg-gray-100 rounded-md h-32 relative overflow-hidden"><div class="load-activity"></div></div>

        <!--      <div class="col-span-1 md:col-span-2 bg-gray-100 rounded-md h-64 relative overflow-hidden"><div class="load-activity"></div></div>-->
        <!--      <div class="col-span-1 md:col-span-2 bg-gray-100 rounded-md h-64 relative overflow-hidden"><div class="load-activity"></div></div>-->

        <!--      <div class="col-span-1 md:col-span-2 bg-gray-100 rounded-md h-64 relative overflow-hidden"><div class="load-activity"></div></div>-->
        <!--      <div class="col-span-1 md:col-span-2 bg-gray-100 rounded-md h-64 relative overflow-hidden"><div class="load-activity"></div></div>-->
      </div>
      <dl v-else class="mt-5 grid grid-cols-1 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow md:grid-cols-3 md:divide-x md:divide-y-0">
        <div class="px-4 py-5 sm:p-6">
          <dt class="text-base font-normal text-gray-900">Omzet</dt>
          <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
            <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
              {{ $filters.currency(statistics.revenue.now) }}
              <span
                  class="ml-2 text-sm font-medium text-gray-500">was {{ $filters.currency(statistics.revenue.previous_week) }}</span>
            </div>

            <div
                v-if="calculatePercentageChange(statistics.revenue.previous_week, statistics.revenue.now) > 0"
                class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
              <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-green-500" viewBox="0 0 20 20"
                   fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"
                      clip-rule="evenodd"/>
              </svg>
              <span class="sr-only"> Difference </span>
              {{ calculatePercentageChange(statistics.revenue.previous_week, statistics.revenue.now) }}%
            </div>
            <div v-else
                 class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
              <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-red-500" viewBox="0 0 20 20"
                   fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z"
                      clip-rule="evenodd"/>
              </svg>
              <span class="sr-only"> Difference </span>
              {{ calculatePercentageChange(statistics.reservations.previous_week, statistics.reservations.now) }}%
            </div>
            <div v-else
                 class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-gray-100 text-gray-800 md:mt-2 lg:mt-0">
              <span class="sr-only"> Difference </span>
              0%
            </div>
          </dd>
        </div>
        <div class="px-4 py-5 sm:p-6">
          <dt class="text-base font-normal text-gray-900">Aantal reserveringen</dt>
          <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
            <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
              {{ statistics.reservations.now }}
              <span
                  class="ml-2 text-sm font-medium text-gray-500">was {{ statistics.reservations.previous_week }}</span>
            </div>

            <div
                v-if="calculatePercentageChange(statistics.reservations.previous_week, statistics.reservations.now) > 0"
                class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
              <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-green-500" viewBox="0 0 20 20"
                   fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"
                      clip-rule="evenodd"/>
              </svg>
              <span class="sr-only"> Increased by </span>
              {{ calculatePercentageChange(statistics.reservations.previous_week, statistics.reservations.now) }}%
            </div>
            <div v-else-if="calculatePercentageChange(statistics.reservations.previous_week, statistics.reservations.now) < 0"
                 class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
              <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-red-500" viewBox="0 0 20 20"
                   fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z"
                      clip-rule="evenodd"/>
              </svg>
              <span class="sr-only"> Increased by </span>
              {{ calculatePercentageChange(statistics.reservations.previous_week, statistics.reservations.now) }}%
            </div>
            <div v-else
                 class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-gray-100 text-gray-800 md:mt-2 lg:mt-0">
              <span class="sr-only"> Difference </span>
              0%
            </div>
          </dd>
        </div>
        <div class="px-4 py-5 sm:p-6">
          <dt class="text-base font-normal text-gray-900">Gemiddelde besteding</dt>
          <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
            <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
              {{ $filters.currency(statistics.average_spending.now) }}
              <span
                  class="ml-2 text-sm font-medium text-gray-500">was {{ $filters.currency(statistics.average_spending.previous_week) }}</span>
            </div>

            <div
                v-if="calculatePercentageChange(statistics.average_spending.previous_week, statistics.average_spending.now) > 0"
                class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
              <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-green-500" viewBox="0 0 20 20"
                   fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"
                      clip-rule="evenodd"/>
              </svg>
              <span class="sr-only"> Difference </span>
              {{ calculatePercentageChange(statistics.average_spending.previous_week, statistics.average_spending.now) }}%
            </div>
            <div v-else-if="calculatePercentageChange(statistics.average_spending.previous_week, statistics.average_spending.now) < 0"
                 class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
              <svg class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-red-500" viewBox="0 0 20 20"
                   fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z"
                      clip-rule="evenodd"/>
              </svg>
              <span class="sr-only"> Difference </span>
              {{ calculatePercentageChange(statistics.average_spending.previous_week, statistics.average_spending.now) }}%
            </div>
            <div v-else
                 class="inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium bg-gray-100 text-gray-800 md:mt-2 lg:mt-0">
              <span class="sr-only"> Difference </span>
              0%
            </div>
          </dd>
        </div>
      </dl>

      <div>
        <div class="mb-4">
          <h1 class="font-semibold mt-12">TRI Berichten</h1>
          <span class="text-sm text-gray-600">TRI, ook wel Timerent Informatie zijn (belangrijke) berichten vanuit het Timerent team voor jou.</span>
        </div>
        <div class="col-span-1 md:col-span-2 overflow-hidden rounded-lg bg-white shadow">
          <div class="divide-y divide-gray-200">
            <div class="flow-root">
              <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                    <tr>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">ID</th>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Titel</th>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Datum</th>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-if="tri.length > 0" @click="currentTRI = t;" v-for="t in tri" :key="t.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer">
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900 font-semibold">#{{ t.id.split('-')[0].toUpperCase() }}</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ t.title }}</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.humanDateTime(t.created_at) }}</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">
                        <p v-if="t.code === 111"><span class="badge badge-primary">Informatie</span></p>
                        <p v-if="t.code === 222"><span class="badge badge-primary">Nieuws & Update</span></p>
                        <p v-if="t.code === 333"><span class="badge badge-warning">(Ver)storing</span></p>
                        <p v-if="t.code === 555"><span class="badge badge-purple">Commercieel</span></p>
                        <p v-if="t.code === 666"><span class="badge badge-warning">Waarschuwing</span></p>
                        <p v-if="t.code === 999"><span class="badge badge-danger">Hoog alert</span></p>
                      </td>
                    </tr>
                    <tr v-else>
                      <td class="text-center py-6" colspan="4">
                        Er zijn geen open TRI's gevonden 🥳
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <Modal v-if="currentTRI" class="">
        <div class="p-4">
          <div class="pb-2 mb-6 flex justify-between border-b-2">
            <div class="text-lg font-semibold">{{currentTRI.title}}</div>
            <i @click="currentTRI = null" class="fa fa-close cursor-pointer"></i>
          </div>
          <div class="mb-6">
            {{ currentTRI.message }}
          </div>
          <div class="mb-6">
            <ul>
              <li><b>Code</b>: {{currentTRI.code}}</li>
              <li><b>Aangemaakt door</b>: {{currentTRI.employee.name}} ({{currentTRI.employee.role.name}})</li>
            </ul>
          </div>
        </div>
      </Modal>

      <div>
        <h1 class="font-semibold mt-12 mb-4">Reserveringen van vandaag</h1>
        <div class="col-span-1 md:col-span-2 overflow-hidden rounded-lg bg-white shadow">
          <div class="divide-y divide-gray-200">
            <div class="flow-root">
              <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                    <tr>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Reservering</th>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Naam</th>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Unit &mdash; Tijd</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-if="reservations.length > 0" v-for="reservation in reservations" :key="reservation.id" @click="this.$router.push({name: 'venues.reservations.edit', params: {venue: this.venue_id, reservation: reservation.id}})" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer">
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900 font-semibold">#{{ reservation.number }}</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ reservation.name }}</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ reservation.email }}</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900" v-for="block in group(reservation)">{{ block[0]['unit_name'] }} &mdash; {{ $filters.humanTime(block[0].from) }} - {{ $filters.humanTime(block[block.length - 1].to) }}</td>
                    </tr>
                    <tr v-else>
                      <td class="text-center py-24" colspan="12">
                        Er zijn geen reserveringen gepland voor vandaag.
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<script>
import {DateTime} from "luxon";
import reservation from "./Settings/Reservation.vue";
import {groupBy} from "lodash";
import Modal from "../Components/Modals/Modal.vue";

export default {
  name: "Index",
  components: {Modal},
  data() {
    return {
      venue_id: this.$route.params.venue,
      from: DateTime.now(),
      statistics: null,
      loading: true,

      tri: [],
      currentTRI: null,

      reservations: [],
    }
  },

  methods: {
    fetchStats() {
      axios.get('/venues/' + this.$route.params.venue + '/statistics')
          .then(response => {
            this.statistics = response.data.data;
            this.loading = false;
          })
    },

    calculatePercentageChange(oldValue, newValue) {
      let changeInAmount = newValue - oldValue;
      let percentage = (changeInAmount / oldValue) * 100;
      if (percentage.toString() === 'Infinity') return 100;
      if (percentage.toString() === 'NaN') return 0;
      return percentage.toFixed(0);
    },

    fetchReservations() {
      axios.get('/venues/' + this.$route.params.venue + '/reservations?date=today&status=paid')
          .then(response => {
            this.reservations = response.data.data;
          })
    },

    group(res) {
      return groupBy(res.timeblocks, 'unit_id');
    },

    fetchTRI() {
      axios.get('/information-messages')
          .then(response => {
            this.tri = response.data.data;
          })
    }
  },

  computed: {
    reservation() {
      return reservation
    },
    greeting() {
      const hour = DateTime.now().hour;
      if (hour >= 0 && hour < 6) return 'Goedenacht';
      if (hour >= 6 && hour < 12) return 'Goedemorgen';
      if (hour >= 12 && hour < 18) return 'Goedemiddag';
      if (hour >= 18 && hour < 24) return 'Goedenavond';
      return hour;
    },
  },

  mounted() {
    this.fetchStats();
    this.fetchReservations();
    this.fetchTRI();
  },
}
</script>