<template>
  <div>

    <h1 class="font-semibold text-xl">{{ greeting }}</h1>
    <h3 class="text-base leading-6 text-gray-900">Beheer hier je account en je reserveringen</h3>

    <div class="mt-6">
        <h1 class="font-semibold mt-12 mb-4">Opkomende reserveringen</h1>
        <div class="col-span-1 md:col-span-2 overflow-hidden rounded-lg bg-white shadow">
          <div class="divide-y divide-gray-200">
            <div class="flow-root">
              <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                    <tr>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Reservering</th>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Datum</th>
                      <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Unit &mdash; Tijd</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-if="reservations.length > 0" v-for="reservation in reservations" @click="this.$router.push({name: 'application.reservations.edit', params: {reservation: reservation.id}})" :key="reservation.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer">
                      <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900 font-semibold">#{{ reservation.number }}</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.humanDate(reservation.date) }}</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900" v-for="block in group(reservation)">{{ block[0]['unit_name'] }} &mdash; {{ $filters.humanTime(block[0].from) }} - {{ $filters.humanTime(block[block.length - 1].to) }}</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-warning mt-2 w-full text-center" v-if="reservation.payment_status === 'open'">Open</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-success mt-2 w-full text-center" v-if="reservation.payment_status === 'paid'">Betaald</td>
                      <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-danger mt-2 w-full text-center" v-if="reservation.payment_status === 'refunded'">Geannuleerd</td>
                    </tr>
                    <tr v-else>
                      <td class="text-center py-24" colspan="12">
                        Je hebt geen reserveringen gepland
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
</template>

<script>
import {DateTime} from "luxon";
import {groupBy} from "lodash";

export default {
  name: "Index",
  data() {
    return {
      venue_id: this.$route.params.venue,
      from: DateTime.now(),
      statistics: null,
      loading: true,

      joke: null,
      joke_punchline: null,

      reservations: [],
    }
  },

  methods: {

    fetchQuote() {
      // axios.get('https://api.api-ninjas.com/v1/facts')
      //     .then(response => {
      //       console.log(response);
      //     })
    },

    fetchReservations() {
      axios.get('/app/members/current/reservations')
          .then(response => {
            this.reservations = response.data.data;
          })
    },

    group(res) {
      return groupBy(res.timeblocks, 'unit_id');
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
    this.fetchReservations();
  },
}
</script>