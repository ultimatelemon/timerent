<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Reserveringen</div>
        <div class="text-sm dark:text-slate-400">Beheer hier alle reserveringen</div>
      </div>
      <div>
<!--        <button @click="$router.push({ name: 'venues.units.create' })" class="btn btn-primary">Nieuwe unit</button>-->
      </div>
    </div>
    <div class="mb-12">
      <div class="font-semibold mb-2">Filteren</div>
      <input class="input" v-model="searchQuery" type="text" placeholder="Zoek op ID of email">
    </div>
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
              <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">#ID</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Datum</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Totaalbedrag</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Aangemaakt op</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Betaalstatus</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="reservations.length > 0" v-for="reservation in reservations" :key="reservation.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer" @click="this.$router.push({name: 'venues.reservations.edit', params: {venue: this.$route.params.venue, reservation: reservation.id}})">
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">#{{ reservation.number }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.humanDate(reservation.date) }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ reservation.email }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.currency(reservation.payment_amount) }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.humanDateTime(reservation.created_at) }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-warning mt-2" v-if="reservation.payment_status === 'open'">Open</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-success mt-2" v-if="reservation.payment_status === 'paid'">Betaald</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-danger mt-2" v-if="reservation.payment_status === 'refunded'">Geannuleerd</td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="12" class="py-12">Er zijn nog geen opkomende reserveringen gepland</td>
            </tr>
            </tbody>
          </table>
          <div v-if="pagination">
            <pagination :pagination="pagination" @changed="fetchDataByPage"></pagination>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from "../../Components/Pagination.vue";
import _ from "lodash";

export default {
  name: "Index",
  components: {Pagination},
  data() {
    return {
      reservations: [],
      pagination: null,
      searchQuery: "",
      searchMethod: _.debounce(() => {
        this.fetchDataByPage(1);
      }, 300),
    }
  },

  methods: {
    fetchDataByPage(page) {
      axios.get('/venues/' + this.$route.params.venue + '/reservations?page=' + page
      + (this.searchQuery ? '&q=' + this.searchQuery : '')
      )
          .then(response => {
            this.reservations = response.data.data;
            this.pagination = response.data.pagination;
          })
    }
  },

  mounted() {
    this.fetchDataByPage(1);
  },

  watch: {
    searchQuery: function () {
      this.searchMethod();
    }
  }
}
</script>