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
          <span class="label label-warning" v-if="ticket.status === 'pending_employee_response'">Waiting for Timerent reply</span>
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
      </div>
      <div class="col-span-2 mt-4">
        <div class="font-semibold">Origineel bericht</div>
        <div>{{ ticket.message }}</div>
      </div>

      <div class="mt-8">
        <div class="font-semibold mb-2">Voeg een nieuw bericht toe aan je ticket.</div>
        <textarea class="input" name="" id="" cols="30" rows="4"></textarea>
        <div class="mt-2 flex justify-end">
          <button class="btn btn-primary">Plaatsen</button>
        </div>
      </div>

      <div class="mt-24">
        <div v-for="message in messages" :key="message.id" class="border p-2 rounded-lg flex my-4" :class="message.user_id ? 'justify-end' : 'justify-start'">
          <div>
            <div>{{message.message}}</div>
            <div class="text-xs mt-2 text-indigo-500 flex justify-between">
              <div>{{ message.employee_name && message.employee_role ? message.employee_name + ' - ' + message.employee_role : 'Jij'}} <span class="text-gray-500 px-12">  {{ $filters.humanDateTime(message.created_at) }}</span></div>
            </div>
          </div>
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
      messages: [
        {
          user_id: null,
          employee_id: 1,
          employee_name: 'Kevin Terpstra',
          employee_role: 'Support Specialist',
          message: 'Hi, ik heb de status nu aangepast, er komt morgen een software update waar de soepstengel is verwijderd. Mocht je nog problemen hebben graag nog een questie aanmaken :)',
          created_at: new Date(),
        },
        {
          user_id: 1,
          employee_id: null,
          employee_name: null,
          employee_role: null,
          message: 'Dank je wel, ik wacht het af!',
          created_at: new Date(),
        },
        {
          user_id: null,
          employee_id: 1,
          employee_name: 'Kevin Terpstra',
          employee_role: 'Support Specialist',
          message: 'Ik ga op onderzoek,!',
          created_at: new Date(),
        },
      ]
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