<template>
  <div v-if="invoice">
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Factuur: #{{ invoice.number }}</div>
        <div class="text-sm">Beheer hier de factuur</div>
      </div>
      <div>
        <span class="label" :class="invoice.paid_at ? 'label-success' : 'label-danger'">{{invoice.paid_at ? 'Betaald' : 'Niet betaald'}}</span>
      </div>
    </div>

    <div class="mt-6">
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-12">
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Short id</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ invoice.number }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Full id</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ invoice.id }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Totaal bedrag</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ $filters.currency(invoice.payment_amount) }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">BTW hoog (21%)</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ $filters.currency(invoice.tax_high) }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">BTW Laag (9%)</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ $filters.currency(invoice.tax_low) }}</dd>
        </div>
<!--        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">-->
<!--          <dt class="text-sm font-semibold leading-6 text-gray-900">Unit(s)</dt>-->
<!--          <dd v-for="block in blocks" class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ block[0]['unit_name'] }} &mdash; {{ $filters.humanTime(block[0].from) }} - {{ $filters.humanTime(block[block.length - 1].to) }}</dd>-->
<!--        </div>-->
<!--        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">-->
<!--          <dt class="text-sm font-semibold leading-6 text-gray-900">Product(en)</dt>-->
<!--          <dd v-if="reservation.products.length > 0" v-for="product in reservation.products" class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">1x {{product.name}}</dd>-->
<!--          <dd v-else class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2"> Er zijn geen producten bijgeboekt </dd>-->
<!--        </div>-->
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Acties</dt>
          <dd class="mt-2 text-sm text-gray-900">
            <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
              <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                <div class="flex w-0 flex-1 items-center">
                  <component :is="PaperClipIcon" class="h-5 w-5"></component>
                  <div class="ml-4 flex min-w-0 flex-1 gap-2">
                    <span class="truncate font-medium">Factuur-{{invoice.number}}.pdf</span>
                    <span class="flex-shrink-0 text-gray-400"></span>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Factuur mailen</a>
                  <span class="font-semibold text-black px-2">—</span>
                  <button @click="downloadInvoice" class="font-medium text-indigo-600 hover:text-indigo-500">Factuur downloaden</button>
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
      invoice_id: this.$route.params.invoice,
      loading: false,
      invoice: null,
      errors: [],

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
      if (this.$route.params.invoice != null) {
        this.loading = true;
        axios.get('/venues/' + this.$route.params.venue + '/invoices/' + this.$route.params.invoice)
            .then(response => {
              this.invoice = response.data.data;
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    postData() {
      if(this.loading) return;
      this.loading = true;
      //
      // if (this.$route.params.reservation != null) {
      //   axios.put('/venues/' + this.$route.params.venue + '/reservations/' + this.$route.params.reservation, this.formData)
      //       .then(response => {
      //         this.$router.push({name: 'venues.reservations.index'});
      //       }).catch(error => {
      //     this.errors = error.response.data.errors;
      //   }).finally(() => {
      //     this.loading = false;
      //   })
      // }
    },

    resendConfirmationMail() {
      axios.post('/venues/' + this.$route.params.venue + '/reservations/' + this.$route.params.reservation + '/resend/confirmation')
          .then(response => {
            alert('Succesvol verzonden');
          })
    },

    downloadInvoice() {
      axios.post('/venues/' + this.$route.params.venue + '/invoices/' + this.invoice.id + '/download')
          .then(response => {
            html2pdf(response.data.data, {
              filename: `Factuur-${this.invoice.number}.pdf`,
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
