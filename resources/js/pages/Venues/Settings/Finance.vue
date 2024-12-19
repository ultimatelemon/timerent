<template>
  <div v-if="venue">
    <div>
      <div class="font-semibold text-lg">Financiële instellingen</div>
      <div class="text-sm dark:text-slate-400">Hoe wil je betalingen ontvangen?</div>
    </div>

    <SuccessAlert v-if="success" @close="success = false">
      <div>Je instellingen zijn opgeslagen</div>
    </SuccessAlert>

    <div class="my-12">
      <div class="flex flex-wrap gap-2 justify-items-stretch">
        <div v-for="psp in psps">
          <div
              @click="formData.payment_service_provider = psp.text_id"
              :class="formData.payment_service_provider === psp.text_id ? 'border-blue-500' : ''"
              class="bg-white rounded-lg border-2 w-48 h-32 leading-none px-4 py-6 transition duration-150 relative cursor-pointer dark:bg-slate-800">
            <img :src="psp.image" :alt="psp.name"
                 class="mx-auto w-16 h-8 object-contain mb-4">
            <h1 class="text-xs text-center text-gray-500 dark:text-slate-400">{{ psp.name }}</h1>
            <div class="absolute bottom-1 right-1 left-1">
              <div v-if="current_psp === psp.text_id" class="text-green-500 p-1 text-xs rounded-full text-center">
                Huidige provider
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="formData.payment_service_provider === 'timerent'">
      <div v-if="venue.stripe_connect_id && venue.stripe_connect_onboarded" class="text-green-500">
        <i class="fa fa-check"></i> Je kunt direct gebruik maken van Timerent Payments
      </div>
      <div v-else>
        <div>
          <p>Om betalingen via Timerent te laten verlopen vragen we je om een aantal stappen te voltooien bij onze
            partner Stripe.</p>
          <br>
          <p class="font-semibold">Belangrijk is dat je je persoonlijke- en bedrijfsgegevens bij de hand houd en de
            verificatie in één keer afmaakt.</p>
        </div>
        <button @click="setupTimerentPayments" class="btn text-white bg-green-500 my-12"><i v-if="loading"
                                                                                            class="fa fa-spinner mr-2 animate-spin"></i>
          Start verificatie
        </button>
      </div>
    </div>
    <div v-else-if="formData.payment_service_provider === 'mollie'">
      <label for="name" class="block text-sm font-medium leading-6 text-gray-900">API key
        <span
            v-if="true"
            class="required-star">*</span> <span class="text-xs dark:text-slate-400">(Je API key is vanwege veiligheid niet meer zichtbaar na het opslaan van je instellingen)</span>
      </label>
      <div class="relative mt-2 rounded-md shadow-sm">
        <input v-model="formData.payment_api_key" type="text" name="name" id="name"
               v-on:keyup.enter="postData"
               :class="errors.name ? 'ring-red-300' : ''"
               class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 input"
               aria-invalid="true" aria-describedby="name-error"/>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
          <svg v-if="errors.name" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor"
               aria-hidden="true">
            <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                  clip-rule="evenodd"/>
          </svg>
        </div>
      </div>
    </div>

    <!--    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">-->
    <!--      <div v-if="formData.payment_service_provider !== 'timerent'">-->
    <!--        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">API key<span-->
    <!--            v-if="true"-->
    <!--            class="required-star">*</span> <span class="text-xs dark:text-slate-400">(Je API key is vanwege veiligheid niet meer zichtbaar na het opslaan van je instellingen)</span></label>-->
    <!--        <div class="relative mt-2 rounded-md shadow-sm">-->
    <!--          <input v-model="formData.payment_api_key" type="text" name="name" id="name"-->
    <!--                 v-on:keyup.enter="postData"-->
    <!--                 :class="errors.name ? 'ring-red-300' : ''"-->
    <!--                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 input"-->
    <!--                 aria-invalid="true" aria-describedby="name-error"/>-->
    <!--          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">-->
    <!--            <svg v-if="errors.name" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor"-->
    <!--                 aria-hidden="true">-->
    <!--              <path fill-rule="evenodd"-->
    <!--                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"-->
    <!--                    clip-rule="evenodd"/>-->
    <!--            </svg>-->
    <!--          </div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div v-else-if="!venue.stripe_connect_id || !venue.stripe_connect_onboarded">-->
    <!--        &lt;!&ndash;        <div v-if="venue.stripe_connect_id && venue.stripe_connect_onboarded" class="text-green-500"><i class="fa fa-check"></i> Je kunt gebruik maken van Timerent Payments</div>&ndash;&gt;-->
    <!--        <div>-->
    <!--          <div>-->
    <!--            <p>Om betalingen via Timerent te laten verlopen vragen we je om een aantal stappen te voltooien bij onze-->
    <!--              partner Stripe.</p>-->
    <!--            <br>-->
    <!--            <p class="font-semibold">Belangrijk is dat je je persoonlijke- en bedrijfsgegevens bij de hand houd en de-->
    <!--              verificatie in één keer afmaakt.</p>-->
    <!--          </div>-->
    <!--          <button @click="setupTimerentPayments" class="btn text-white bg-green-500 my-12"><i v-if="loading"-->
    <!--                                                                                              class="fa fa-spinner mr-2 animate-spin"></i>-->
    <!--            Start verificatie-->
    <!--          </button>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div v-else-if="venue.stripe_connect_id && venue.stripe_connect_onboarded">-->
    <!--        Je account is onboarded voor Timerent Payments en je kunt direct betalingen ontvangen.-->
    <!--      </div>-->
    <!--    </div>-->

    <Modal v-if="openPaymentsSetupModal">
      <div class="p-3 space-y-3">
        <h1 class="font-semibold">Rond je verificatie af</h1>
        <p class="text-sm">Klik op de knop om je verificatie in 1 keer af te ronden.</p>
        <div class="flex justify-end">
          <a :href="paymentsSetupURL" @click="openPaymentsSetupModal = false; paymentsSetupURL = null;"
             target="_blank"
             class="btn btn-primary">Afronden</a>
        </div>
      </div>
    </Modal>

    <div class="mt-8 flex justify-end">
      <button @click="postData" class="btn btn-lg"
              :class="(this.loading ? 'btn-secondary opacity-50 cursor-not-allowed' : 'btn-primary')">
        <i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Opslaan
      </button>
    </div>
  </div>
</template>

<script>

import Modal from "../../Components/Modals/Modal.vue";
import SuccessAlert from "../../Components/Alerts/SuccessAlert.vue";

export default {
  name: "Finance",
  components: {SuccessAlert, Modal},
  data() {
    return {

      psps: [],
      venue: null,

      loading: false,
      success: false,
      errors: [],

      current_psp: null,

      openPaymentsSetupModal: false,
      paymentsSetupURL: null,

      formData: {
        payment_service_provider: null,
        payment_api_key: null,
      },
    }
  },

  methods: {
    postData() {
      if (this.loading) return;
      this.loading = true;

      axios.put('/venues/' + this.$route.params.venue + '/settings/finance', this.formData)
          .then(response => {
            this.success = true;
            this.errors = [];
            this.fetchData();
          })
          .catch(e => {
            this.errors = e.response.data.errors;
            this.success = false;
          })
          .finally(() => {
            this.loading = false;
          })
    },

    setupTimerentPayments() {
      if (this.loading) return;
      this.loading = true;

      axios.post('/venue/' + this.$route.params.venue + '/payments/setup')
          .then(response => {
            console.log(response.data.data);
            this.openPaymentsSetupModal = true;
            this.paymentsSetupURL = response.data.data;
          })
          .catch(e => {
            console.log("ERR", e.response.data);
          })
          .finally(() => {
            this.loading = false;
          })
    },

    fetchData() {
      axios.get('/venues/' + this.$route.params.venue + '/settings/finance')
          .then(response => {
            this.venue = response.data.data;
            this.formData.payment_service_provider = response.data.data.payment_service_provider ?? 'timerent';
            this.current_psp = response.data.data.payment_service_provider;
          })
    },

    fetchPaymentProviders() {
      axios.get('/paymentproviders/available')
          .then(response => {
            this.psps = response.data.data;
          })
    },
  },

  mounted() {
    this.fetchPaymentProviders();
    this.fetchData();
  },
}
</script>