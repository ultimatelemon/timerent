<template>
  <div v-if="member">
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Member: #{{ member.id.split('-')[0].toUpperCase() }}</div>
        <div class="text-sm dark:text-slate-400">Beheer de huidige gebruiker</div>
      </div>
    </div>
    <div class="flex gap-6 text-sm border-b border-gray-100 pb-8">
      <div>
        <router-link :to="{name: 'venues.members.edit', params: {venue: this.$route.params.venue, member: this.$route.params.member}}" active-class="border-indigo-500" class="border-b-2 hover:border-indigo-500 pb-1">Gebruiker</router-link>
      </div>
      <div>
        <router-link :to="{name: 'venues.members.reservations', params: {venue: this.$route.params.venue, member: this.$route.params.member}}" active-class="border-indigo-500" class="border-b-2 hover:border-indigo-500 pb-1">Reserveringen</router-link>
      </div>
    </div>
    <div class="mt-6 mb-12">
      <div class="font-semibold mb-2">Filteren</div>
      <input class="input" v-model="searchQuery" type="text" placeholder="Zoek op ID">
    </div>
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 ">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
              <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">#ID</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Datum</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Totaalbedrag</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Aangemaakt op</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Betaalstatus</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="reservations.length > 0" v-for="reservation in reservations" :key="reservation.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer" @click="this.$router.push({name: 'venues.reservations.edit', params: {reservation: reservation.id, venue: this.$route.params.venue}})">
              <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-900 font-semibold">#{{ reservation.number }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.humanDate(reservation.date) }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.currency(reservation.payment_amount) }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.humanDateTime(reservation.created_at) }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-success" v-if="reservation.payment_status ==='paid'">Betaald</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-warning" v-if="reservation.payment_status ==='open'">Open</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-danger" v-if="reservation.payment_status ==='expired'">Verlopen</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900 label label-danger" v-if="reservation.payment_status ==='refunded'">Geannuleerd</td>
            </tr>
            <tr v-else>
              <td class="text-center py-24" colspan="12">
                Deze gebruiker heeft nog geen reserveringen geplaatst.
              </td>
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
      member: null,

      pagination: null,

      searchQuery: "",
      searchMethod: _.debounce(() => {
        this.fetchDataByPage(1);
      }, 300),
    }
  },

  methods: {
    fetchData() {
      if (this.$route.params.member != null) {
        this.loading = true;
        axios.get('/venues/' + this.$route.params.venue + '/members/' + this.$route.params.member)
            .then(response => {
              this.member = response.data.data;
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    fetchDataByPage(page) {
      axios.get('/venues/' + this.$route.params.venue + '/members/'+this.$route.params.member+'/reservations?page=' + page
          + (this.searchQuery ? '&q=' + this.searchQuery : '')
      )
          .then(response => {
            this.reservations = response.data.data;
            // alert(response.data.data[0].name)
            this.pagination = response.data.pagination;
          })
    }
  },

  mounted() {
    this.fetchDataByPage(1);
    this.fetchData();
  },

  watch: {
    searchQuery: function () {
      this.searchMethod();
    }
  }
}
</script>