<template>
  <div v-if="reservation">
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-xl">Reservering: #{{ reservation.number }}</div>
        <div class="text-base leading-6 text-gray-900">Beheer hier de reservering</div>
      </div>
      <div v-if="reservation.canceled_at" class="text-red-500">
        Deze reservering is geannuleerd
      </div>
    </div>

    <div class="mt-6">
      <dl class="grid grid-cols-1 sm:grid-cols-2">
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
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ reservation.email }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Commentaar/Notities</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ reservation.comments ?? 'Er zijn geen notities' }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Unit(s)</dt>
          <dd v-for="block in blocks" class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ block[0]['unit_name'] }} &mdash; {{ $filters.humanTime(block[0].from) }} - {{ $filters.humanTime(block[block.length - 1].to) }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Product(en)</dt>
          <dd v-if="reservation.products.length > 0" v-for="product in reservation.products" class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">1x {{product.name}}</dd>
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
                    <span class="truncate font-medium">Factuur-9C435DE2.pdf</span>
                    <span class="flex-shrink-0 text-gray-400"></span>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <button @click="downloadInvoice" class="font-medium text-indigo-600 hover:text-indigo-500">Factuur downloaden</button>
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
                  <button @click="resendConfirmationMail" class="font-medium text-indigo-600 hover:text-indigo-500">Bevestiging opnieuw mailen</button>
                </div>
              </li>
            </ul>
          </dd>
        </div>
      </dl>
    </div>
    <div class="flex justify-end gap-4">
      <button @click="$router.go(-1)" class="btn btn-secondary">Terug</button>
      <button v-if="reservation.cancel_allowed" @click="!loading ? cancelModal = true : null" :class="loading ? 'btn btn-secondary opacity-50 cursor-not-allowed' : 'btn btn-danger'"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Annuleren</button>
    </div>

    <transition name="modalfade">
      <modal v-if="cancelModal" @close="cancelModal = false">
        <div class="px-1 border-b border-gray-200 flex items-center justify-between">
          <p class="font-medium p-4">Reservering annuleren</p>
          <button class="p-4" @click="cancelModal = false"><i class="far fa-times"></i></button>
        </div>
        <div v-if="errorMessage" class="p-6 text-red-500"><i class="fa fa-triangle-exclamation"></i> {{ errorMessage }}</div>
        <div v-else class="p-6">Weet je zeker dat je deze reservering wilt annuleren?</div>
        <div class="flex justify-end gap-4 bg-gray-50 px-5 py-3">
          <button :class="loading ? 'btn btn-secondary opacity-50 cursor-not-allowed' : 'btn btn-danger'" @click="cancel">
            <i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Annuleren
          </button>
        </div>
      </modal>
    </transition>

  </div>
</template>

<script>
import {groupBy} from "lodash";
import {EnvelopeIcon, PaperClipIcon} from "@heroicons/vue/24/outline/index.js";
import Modal from "../../../Components/Modal.vue";
import html2pdf from "html2pdf.js";

export default {
  name: "Edit",
  components: {Modal},
  data() {
    return {
      reservation_id: this.$route.params.reservation,
      loading: false,
      reservation: null,
      errors: [],
      blocks: null,
      cancelModal: false,
      errorMessage: null,

      formData: {
        name: "",
        description: "",
      },
    }
  },

  methods: {
    PaperClipIcon,
    EnvelopeIcon,
    fetchData() {
      axios.get('/app/members/current/reservations/' + this.$route.params.reservation)
          .then(response => {
            this.reservation = response.data.data;
            this.group();
          })
          .finally(() => {

          })
    },

    cancel() {
      if(this.loading) return;
      this.loading = true;
      axios.post('/app/members/current/reservations/' + this.$route.params.reservation + '/cancel')
          .then(response => {
            this.loading = false;
            this.errorMessage = null;
            this.$router.push({name: 'application.reservations.index'});
          })
          .catch((e) => {
            this.loading = false;
            if(e.response.data.errors.message) this.errorMessage = e.response.data.errors.message
            console.log(e.response.data.errors.message)
          })
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
      axios.post('/app/members/current/invoices/'+ this.reservation.invoice.id + '/download')
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
