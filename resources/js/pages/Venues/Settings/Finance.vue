<template>
  <div v-if="venue">
    <div class="font-semibold text-lg mb-4">Financiën</div>
    <div class="mb-12">
      <div class="pb-2">Hoe wil je betalingen ontvangen?</div>
      <div class="flex flex-wrap gap-2 justify-items-stretch">
        <div v-for="psp in psps">
          <div
              @click="formData.payment_provider = psp.text_id"
              :class="formData.payment_provider === psp.text_id ? 'border-blue-500' : ''"
              class="bg-white rounded-lg border-2 w-48 h-32 leading-none px-4 py-6 transition duration-150 relative cursor-pointer">
            <img :src="psp.image" :alt="psp.name"
                 class="mx-auto w-16 h-8 object-contain mb-4">
            <h1 class="text-xs text-center text-gray-500">{{psp.name}}</h1>
            <div class="absolute bottom-1 right-1 left-1">
              <div v-if="current_psp === psp.text_id" class="text-green-500 p-1 text-xs rounded-full text-center">
                Huidige provider
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <div v-if="formData.payment_provider !== 'timerent'">
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">API key<span
            v-if="true"
            class="required-star">*</span> <span class="text-xs">(Je API key is vanwege veiligheid niet meer zichtbaar na het opslaan van je instellingen)</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.payment_api_key" type="text" name="name" id="name"
                 v-on:keyup.enter="postData"
                 :class="errors.name ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.name" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
      </div>
      <div v-else>
<!--        <div v-if="venue.stripe_connect_id && venue.stripe_connect_onboarded" class="text-green-500"><i class="fa fa-check"></i> Je kunt gebruik maken van Timerent Payments</div>-->
        <div v-if="!venue.stripe_connect_id || !venue.stripe_connect_onboarded">
          <div>
            <p>Om betalingen via Timerent te laten verlopen vragen we je om een aantal stappen te voltooien bij onze partner Stripe.</p>
            <br>
            <p class="font-semibold">Belangrijk is dat je je persoonlijke- en bedrijfsgegevens bij de hand houd en de verificatie in één keer afmaakt.</p>
          </div>
          <button @click="setupTimerentPayments" class="btn text-white bg-green-500 my-12"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Start verificatie</button>
        </div>
      </div>
    </div>

    <Modal v-if="openPaymentsSetupModal">
      <div class='fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity'></div>

      <div class='fixed inset-0 z-10 overflow-y-auto'>
        <div class='flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0'>
          <div
              class='relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6'>
            <div class='sm:flex sm:items-start'>
              <div
                  class='mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10'>
                <svg data-tooltip-target="tooltip-default" class='z-10 h-6 w-6 text-red-600' fill='none' viewBox='0 0 24 24' stroke-width='1.5'
                     stroke='currentColor' aria-hidden='true'>
                  <path stroke-linecap='round' stroke-linejoin='round'
                        d='M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' />
                </svg>

              </div>
              <div class='mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left'>
                <h3 class='text-base font-semibold leading-6 text-gray-900' id='modal-title'>Let op</h3>
                <div class='mt-2'>
                  <p class='text-sm text-gray-500'>De betalingsetup opent in een nieuwe pagina.</p>
                </div>
              </div>
            </div>
            <div class='mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-4'>
              <a :href="paymentsSetupURL" target='blank' type='button' class='btn btn-success'>Starten &rarr;</a>
              <button @click='openPaymentsSetupModal = !openPaymentsSetupModal' type='button'
                      class='mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto'>
                Sluiten
              </button>
            </div>
          </div>
        </div>
      </div>
    </Modal>

    <div class="mt-12 flex justify-end gap-4">
      <button :disabled="!venue.stripe_connect_id && formData.payment_provider === 'timerent'" @click="postData" class="btn btn-lg btn-primary">Instellingen opslaan</button>
    </div>
  </div>
</template>

<script>

import Modal from "../../Components/Modal.vue";

export default {
  name: "Finance",
  components: {Modal},
  data() {
    return {
      errors: [],
      settings: [],
      venue: null,
      loading: false,

      psps: [],
      current_psp: '',

      openPaymentsSetupModal: false,
      paymentsSetupURL: '',

      formData: {
        payment_provider: '',
        payment_api_key: '',
      },
    }
  },

  methods: {

    fetchVenue() {
      axios.get('/venues/' + this.$route.params.venue + '/settings/payment')
          .then(response => {
            this.venue = response.data.data.venue;
            this.settings = response.data.data.settings;

            this.formData.payment_provider = response.data.data.settings.payment_provider;
            this.current_psp = response.data.data.settings.payment_provider;
          })
    },

    postData() {
      axios.put('/venues/' + this.$route.params.venue + '/settings/payment', this.formData)
          .then(response => {
            alert('Instellingen opgeslagen')
            this.formData.payment_api_key = '';
            this.fetchVenue()
          })
          .catch(e => {
            alert('Er is een onbekende fout opgetreden');
            console.log(e.response.data)
          })
    },

    fetchPaymentProviders() {
      axios.get('/paymentproviders/available')
          .then(response => {
            this.psps = response.data.data;
          })
    },

    setupTimerentPayments() {
      if(this.loading) return;
      this.loading = true;
      axios.post('/venue/' + this.$route.params.venue + '/payments/setup')
          .then(response => {
            this.openPaymentsSetupModal = true;
            this.paymentsSetupURL = response.data.data;
          })
          .catch(e => {
            console.log("ERR", e.response.data);
          })
          .finally(() => {
            this.loading = false;
          })
    }
  },

  mounted() {
    this.fetchVenue()
    this.fetchPaymentProviders()
  }
}
</script>