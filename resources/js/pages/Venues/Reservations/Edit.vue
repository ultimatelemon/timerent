<template>
  <div v-if="reservation">
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Reservering: #{{ reservation.number }}</div>
        <div class="text-sm dark:text-slate-400">Beheer hier de reservering</div>
      </div>
      <div v-if="reservation.member.id">
        <button @click="this.$router.push({name: 'venues.members.edit', params: {venue: this.$route.params.venue, member: reservation.member.id}})" class="btn btn-primary">Bekijk gebruiker</button>
      </div>
    </div>

    <div class="mt-6">
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-12">
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Naam</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ reservation.name }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Datum</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ $filters.humanDate(reservation.date) }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Email adres</dt>
          <input class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2 input" v-model="formData.email">
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Commentaar / Notities (zichtbaar voor klant)</dt>
          <textarea class="mt-1 p-2 text-sm leading-6 text-gray-700 sm:mt-2 input" v-model="formData.comments"></textarea>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Telefoonnummer</dt>
          <input class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2 input" v-model="formData.phone_number">
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Unit(s)</dt>
          <dd v-for="block in blocks" class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ block[0]['unit_name'] }} &mdash; {{ $filters.humanTime(block[0].from) }} - {{ $filters.humanTime(block[block.length - 1].to) }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Product(en)</dt>
          <dd v-if="reservation.products.length > 0" v-for="product in reservation.products" class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{product.count}}x {{product.name}}</dd>
          <dd v-else class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2"> Er zijn geen producten bijgeboekt </dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Acties</dt>
          <dd class="mt-2 text-sm text-gray-900">
            <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
              <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                <div class="flex w-0 flex-1 items-center">
                  <component :is="PaperClipIcon" class="h-5 w-5"></component>
                  <div class="ml-4 flex min-w-0 flex-1 gap-2">
                    <span class="truncate font-medium">Factuur-{{reservation.invoice.number}}.pdf</span>
                    <span class="flex-shrink-0 text-gray-400"></span>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-slate-400">Factuur opnieuw mailen</a>
                  <span class="font-semibold text-black px-2 dark:text-slate-400">—</span>
                  <button @click="downloadInvoice" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-slate-400">Factuur downloaden</button>
                </div>
              </li>
              <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                <div class="flex w-0 flex-1 items-center">
                  <component :is="EnvelopeIcon" class="h-5 w-5"></component>
                  <div class="ml-4 flex min-w-0 flex-1 gap-2">
                    <span class="truncate font-medium">Reserveringsbevestiging</span>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <button @click="resendConfirmationMail" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-slate-400">Bevestiging opnieuw mailen</button>
                </div>
              </li>
            </ul>
          </dd>
        </div>
      </dl>
    </div>
    <div class="flex justify-end gap-4">
      <button @click="$router.go(-1)" class="btn btn-secondary">Terug</button>
      <button @click="postData" :class="loading ? 'btn btn-secondary opacity-50 cursor-not-allowed' : 'btn btn-primary'"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Aanpassingen opslaan</button>
    </div>
  </div>
</template>

<script>
import {groupBy} from "lodash";
import {ArrowTopRightOnSquareIcon, EnvelopeIcon, PaperClipIcon} from "@heroicons/vue/24/outline/index.js";
import html2pdf from "html2pdf.js";

export default {
  name: "Edit",
  data() {
    return {
      reservation_id: this.$route.params.reservation,
      loading: false,
      reservation: null,
      errors: [],
      blocks: null,

      formData: {
        phone_number: "",
        email: "",
        comments: "",
      },
    }
  },

  methods: {
    ArrowTopRightOnSquareIcon,
    PaperClipIcon,
    EnvelopeIcon,
    fetchData() {
      if (this.$route.params.reservation != null) {
        this.loading = true;
        axios.get('/venues/' + this.$route.params.venue + '/reservations/' + this.$route.params.reservation)
            .then(response => {
              this.reservation = response.data.data;
              this.formData.phone_number = response.data.data.phone_number;
              this.formData.email = response.data.data.email;
              this.formData.comments = response.data.data.comments;
              this.group();
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    postData() {
      if(this.loading) return;
      this.loading = true;

      if (this.$route.params.reservation != null) {
        axios.put('/venues/' + this.$route.params.venue + '/reservations/' + this.$route.params.reservation, this.formData)
            .then(response => {
              this.$router.push({name: 'venues.reservations.index'});
            }).catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      }
    },

    resendConfirmationMail() {
      axios.post('/venues/' + this.$route.params.venue + '/reservations/' + this.$route.params.reservation + '/resend/confirmation')
          .then(response => {
            alert('Succesvol verzonden');
          })
    },

    group() {
      this.blocks = groupBy(this.reservation.timeblocks, 'unit_id');
    },

    downloadInvoice() {
      axios.post('/venues/' + this.$route.params.venue + '/invoices/' + this.reservation.invoice.id + '/download')
          .then(response => {
            html2pdf(response.data.data, {
              filename: 'Factuur.pdf',
              margin: 1,
            })
            // console.log(response.data.data);
          })
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>
