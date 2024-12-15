<template>
  <div>
    <div>
      <div class="font-semibold text-lg">Algemene instellingen</div>
      <div class="text-sm dark:text-slate-400">Beheer hier de algemene instellingen voor je onderneming</div>
    </div>

    <div v-if="success" class="my-12 alert alert-success">
      <div>Je instellingen zijn opgeslagen</div>
      <i class="fa fa-close cursor-pointer" @click="success = false"></i>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-12">
      <div>
        <label for="reservation_prefix" class="block text-sm font-medium leading-6 text-gray-900"> Prefix <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.reservation_prefix" type="text" name="reservation_prefix" id="reservation_prefix"
                 v-on:keyup.enter="postData"
                 :class="errors.reservation_prefix ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 input"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.reservation_prefix" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.reservation_prefix" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.reservation_prefix[0] }}</p>
      </div>
      <div>
        <label for="cancellation_hours" class="block text-sm font-medium leading-6 text-gray-900"> Annuleren maximaal X uur van te voren <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.cancellation_hours" type="text" name="cancellation_hours" id="cancellation_hours"
                 v-on:keyup.enter="postData"
                 :class="errors.cancellation_hours ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 input"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.cancellation_hours" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.cancellation_hours" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.cancellation_hours[0] }}</p>
      </div>
    </div>
    <div class="mt-8 flex justify-end">
      <button @click="postData" class="btn btn-lg"
              :class="(this.loading ? 'btn-secondary opacity-50 cursor-not-allowed' : 'btn-primary')">
        <i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Opslaan
      </button>
    </div>
  </div>
</template>

<script>

export default {
  name: "Reservation",
  data() {
    return {

      loading: false,
      success: false,
      errors: [],

      formData: {
        reservation_prefix: null,
        cancellation_hours: null,
      },
    }
  },

  methods: {
    postData() {
      if(this.loading) return;
      this.loading = true;

      axios.put('/venues/' + this.$route.params.venue + '/settings/reservations', this.formData)
          .then(response => {
            this.success = true;
            this.errors = [];
          })
          .catch(e => {
            this.errors = e.response.data.errors;
            this.success = false;
          })
          .finally(() => {
            this.loading = false;
          })
    },

    fetchData() {
      axios.get('/venues/' + this.$route.params.venue + '/settings/reservations')
          .then(response => {
            this.formData.reservation_prefix = response.data.data.reservation_prefix;
            this.formData.cancellation_hours = response.data.data.cancellation_hours;
          })
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>