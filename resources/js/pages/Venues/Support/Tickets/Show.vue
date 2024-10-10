<template>
  <div v-if="ticket">
    <div class="mb-12">
      <div>
        <div class="font-semibold text-lg">Ticket details #{{ticket.human_id}}</div>
        <div class="text-sm">Bekijk hier de ticket of voeg een nieuw bericht toe aan je ticket.</div>
      </div>
    </div>

    <div class="lg:col-start-3 lg:row-end-1">
      <h2 class="sr-only">Summary</h2>
      <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-900/5">
        <dl class="flex flex-wrap">
          <div class="flex-auto pl-6 pt-6">
            <dt class="text-sm font-semibold leading-6 text-gray-900">Titel</dt>
            <dd class="mt-1 text-base font-semibold leading-6 text-gray-900">{{ticket.title}}</dd>
          </div>
          <div class="flex-none self-end px-6 pt-4">
            <dt class="sr-only">Status</dt>
            <span class="badge badge-success" v-if="ticket.status === 'open'">Open</span>
            <span class="badge" v-if="ticket.status === 'pending_employee_response'">Waiting for Timerent response</span>
            <span class="badge badge-warning" v-if="ticket.status === 'pending_customer_response'">Waiting for your response</span>
            <span class="badge badge-success" v-if="ticket.status === 'solved'">Solved</span>
            <span class="badge badge-warning" v-if="ticket.status === 'in_progress'">In progress</span>
            <span class="badge badge-danger" v-if="ticket.status === 'escalated'">Escalated</span>
            <span class="badge badge-warning" v-if="ticket.status === 'on_hold'">On hold</span>
          </div>
          <div class="mt-6 flex w-full flex-none gap-x-4 border-t border-gray-900/5 px-6 pt-6">
            <dt class="flex-none">
              <span class="sr-only">Client</span>
              <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
              </svg>
            </dt>
            <dd class="text-sm font-medium leading-6 text-gray-900">{{ ticket.user.name }}</dd>
          </div>
          <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
            <dt class="flex-none">
              <span class="sr-only">Due date</span>
              <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                <path d="M5.25 12a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H6a.75.75 0 0 1-.75-.75V12ZM6 13.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V14a.75.75 0 0 0-.75-.75H6ZM7.25 12a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H8a.75.75 0 0 1-.75-.75V12ZM8 13.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V14a.75.75 0 0 0-.75-.75H8ZM9.25 10a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H10a.75.75 0 0 1-.75-.75V10ZM10 11.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V12a.75.75 0 0 0-.75-.75H10ZM9.25 14a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H10a.75.75 0 0 1-.75-.75V14ZM12 9.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V10a.75.75 0 0 0-.75-.75H12ZM11.25 12a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H12a.75.75 0 0 1-.75-.75V12ZM12 13.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V14a.75.75 0 0 0-.75-.75H12ZM13.25 10a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H14a.75.75 0 0 1-.75-.75V10ZM14 11.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V12a.75.75 0 0 0-.75-.75H14Z" />
                <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z" clip-rule="evenodd" />
              </svg>
            </dt>
            <dd class="text-sm leading-6 text-gray-500">
              <time datetime="2023-01-31">{{ $filters.humanDateTime(ticket.created_at) }}</time>
            </dd>
          </div>
<!--          <div class="mt-4 flex w-full flex-none gap-x-4 px-6">-->
<!--            <dt class="flex-none">-->
<!--              <span class="sr-only">Status</span>-->
<!--              <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">-->
<!--                <path fill-rule="evenodd" d="M2.5 4A1.5 1.5 0 0 0 1 5.5V6h18v-.5A1.5 1.5 0 0 0 17.5 4h-15ZM19 8.5H1v6A1.5 1.5 0 0 0 2.5 16h15a1.5 1.5 0 0 0 1.5-1.5v-6ZM3 13.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75Zm4.75-.75a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5Z" clip-rule="evenodd" />-->
<!--              </svg>-->
<!--            </dt>-->
<!--            <dd class="text-sm leading-6 text-gray-500">Paid with MasterCard</dd>-->
<!--          </div>-->
        </dl>
        <div class="mt-6 border-t border-gray-900/5 px-6 py-6">
<!--          <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Download receipt <span aria-hidden="true">&rarr;</span></a>-->
          <button @click="closeTicket()" class="btn btn-danger">Ticket sluiten</button>
        </div>
      </div>
    </div>
    <div class="mt-12">
      <div>
        <label for="comment" class="block text-sm font-medium leading-6 text-gray-900">Bericht toevoegen aan ticket</label>
        <div class="mt-2 space-y-4 flex flex-col">
          <textarea v-model="message" rows="4" autofocus name="comment" id="comment" placeholder="Voeg hier een bericht toe aan je ticket" class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" :class="errors.message ? 'ring-red-500' : ''" />
          <span v-if="errors.message" class="text-red-500 text-sm">{{ errors.message[0] }}</span>
          <button @click="postData" :class="loading ? 'btn-secondary' : 'btn-primary'" class="btn place-self-end"><i v-if="loading" class="fa fa-spinner text-sm mr-2 animate-spin"></i>Plaatsen</button>
        </div>
      </div>
    </div>

    <div class="mt-12">
      <div class="flex flex-col gap-8">
        <div v-for="tm in ticket.messages" class="flex flex-col">
          <div v-if="!tm.is_employee" class="w-2/3 bg-gray-500 text-white p-3 rounded-tl-xl rounded-bl-xl rounded-tr-xl place-self-end flex flex-col">
            <p class="max-w-full text-md break-words">{{ tm.message }}</p>
            <p class="text-xs pt-6 place-self-end">Kevin Terpstra &#8901; {{ $filters.humanDateTime(tm.created_at) }}</p>
          </div>
          <div v-else class="w-2/3 bg-indigo-700 text-white p-3 rounded-tl-xl rounded-br-xl rounded-tr-xl">
            <p class="max-w-full break-words">
              {{ tm.message }}</p>
            <p class="text-xs pt-6">{{tm.user.name}} &#8901; {{ tm.role_name }} <br><br>{{ $filters.humanDateTime(tm.created_at) }}</p>
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
      loading: false,
      message: null,
      sortedMessages: [],
      errors: [],
    }
  },

  methods: {
    fetchData() {
     axios.get('/venues/' + this.$route.params.venue + '/tickets/' + this.$route.params.ticket)
         .then(response => {
           this.ticket = response.data.data;
           this.sortedMessages = this.ticket.messages.slice().sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
         })
    },

    postData() {
      if(this.loading) return;
      this.loading = true;
      axios.put('/venues/' + this.$route.params.venue + '/tickets/' + this.$route.params.ticket, {
        message: this.message
      })
          .then(() => {
            this.fetchData();
            this.message = null;
            this.errors = [];
          })
          .catch(e => {
            console.log(e.response.data);
            this.errors = e.response.data.errors;
          })
          .finally(() => {
            this.loading = false;
          })
    },

    closeTicket() {
      axios.post('/venues/' + this.$route.params.venue + '/tickets/' + this.$route.params.ticket + '/close')
          .then(response => {
            this.$router.push({ name: 'venues.support.tickets.index', params: {venue: this.$route.params.venue} });
          })
    }
  },

  computed: {
    // sortedMessages() {
    //
    // }
  },

  mounted() {
    this.fetchData();
  },
}
</script>