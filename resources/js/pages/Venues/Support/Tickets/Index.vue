<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Support tickets</div>
        <div class="text-sm dark:text-slate-400">Bekijk hier je open tickets of maak een nieuw ticket aan.</div>
      </div>
      <div>
        <button @click="$router.push({ name: 'venues.support.tickets.create' })" class="btn btn-primary">Ticket aanmaken</button>
      </div>
    </div>

    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
              <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"># ID</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Titel</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Aangemaakt door</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="tickets.length >= 1" v-for="ticket in tickets" :key="ticket.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer" @click="this.$router.push({name: 'venues.support.tickets.show', params: {venue: this.$route.params.venue, ticket: ticket.id}})">
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">#{{ ticket.human_id }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ ticket.title }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">
                <span v-if="ticket.type === 'software_issue'">Software issue</span>
                <span v-if="ticket.type === 'question'">Question</span>
                <span v-if="ticket.type === 'feedback'">Feedback</span>
                <span v-if="ticket.type === 'critical'">Critical</span>
              </td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">
                <span class="badge badge-success" v-if="ticket.status === 'open'">Open</span>
                <span class="badge" v-if="ticket.status === 'pending_employee_response'">Waiting for Timerent response</span>
                <span class="badge badge-warning" v-if="ticket.status === 'pending_customer_response'">Waiting for your response</span>
                <span class="badge badge-success" v-if="ticket.status === 'solved'">Solved</span>
                <span class="badge badge-warning" v-if="ticket.status === 'in_progress'">In progress</span>
                <span class="badge badge-danger" v-if="ticket.status === 'escalated'">Escalated</span>
                <span class="badge badge-warning" v-if="ticket.status === 'on_hold'">On hold</span>
              </td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ ticket.user.name }}</td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="12" class="py-12">
                Er zijn geen open tickets gevonden 🥳
              </td>
            </tr>
            </tbody>
          </table>
          <div v-if="pagination && tickets.length > 0">
            <pagination :pagination="pagination" @changed="fetchDataByPage"></pagination>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from "../../../Components/Pagination.vue";
import _ from "lodash";

export default {
  name: "Index",
  components: {Pagination},
  data() {
    return {
      pagination: null,
      searchQuery: null,
      searchMethod: _.debounce(() => {
        this.fetchDataByPage(1);
      }, 300),

      tickets: []
    }
  },

  methods: {
    fetchDataByPage(page) {
      axios.get('/venues/' + this.$route.params.venue + '/tickets?page=' + page
        + (this.searchQuery ? '&q=' + this.searchQuery : '')
      )
          .then(response => {
            this.tickets = response.data.data;
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