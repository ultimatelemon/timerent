<template>
  <div v-if="ticket">
    <div class="mb-12">
      <div>
        <div class="font-semibold text-lg">Ticket details #{{ticket.human_id}}</div>
        <div class="text-sm">Bekijk hier de ticket of voeg een nieuw bericht toe aan je ticket.</div>
      </div>
    </div>

    <div>
      <div class="font-semibold mb-8">
        Ticket informatie
        <p class="font-normal text-sm">Informatie over je ticket</p>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div>
          <div class="font-semibold">Titel</div>
          <div>{{ ticket.title }}</div>
        </div>
        <div>
          <div class="font-semibold">Type</div>
          <div>{{ $filters.firstLetterUppercase(ticket.type) }}</div>
        </div>
        <div>
          <div class="font-semibold">Status</div>
          <span class="label label-info" v-if="ticket.status === 'open'">Open</span>
          <span class="label label-warning" v-if="ticket.status === 'in_progress'">In progress</span>
          <span class="label label-warning" v-if="ticket.status === 'pending_customer_response'">Waiting for your reply</span>
          <span class="label label-success" v-if="ticket.status === 'solved'">Solved</span>
          <span class="label label-danger" v-if="ticket.status === 'escalated'">Escalated</span>
          <span class="label label-info" v-if="ticket.status === 'on_hold'">On hold</span>
          <span class="label label-success" v-if="ticket.status === 'cancelled'">Cancelled</span>
          <span class="label label-info" v-if="ticket.status === 'reopened_by_employee'">Reopened by Timerent</span>
        </div>
        <div>
          <div class="font-semibold">Toegewezen aan</div>
          <div>{{ ticket.employee?.name ?? 'Er is nog geen medewerker toegewezen' }}</div>
        </div>
        <div class="col-span-2">
          <div class="font-semibold">Jouw bericht</div>
          <div>{{ ticket.message }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Show",

  data() {
    return {
      ticket: null,
    }
  },

  methods: {
    fetchData() {
     axios.get('/venues/' + this.$route.params.venue + '/tickets/' + this.$route.params.ticket)
         .then(response => {
           this.ticket = response.data.data;
         })
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>