<template>
  <div>
    <main class="grid min-h-full place-items-center bg-white px-6 py-24 sm:py-32 lg:px-8">
      <div class="text-center" v-if="!paid && loading">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
          <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
        </div>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">We controleren je betaling <i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> </h1>
        <p class="mt-6 text-base leading-7 text-gray-600">Scherm niet afsluiten.</p>
      </div>
      <div class="text-center" v-if="paid && !loading">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
          <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
        </div>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">Betaling is gelukt 🥳</h1>
        <p class="mt-6 text-base leading-7 text-gray-600">Je kunt dit scherm nu sluiten.</p>
      </div>
      <div class="text-center" v-if="!paid && !loading">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
          <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">Betaling is mislukt 😥</h1>
        <p class="mt-6 text-base leading-7 text-gray-600">Je kunt dit scherm nu sluiten.</p>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: "ReservationConfirmation",
  data() {
    return {
      reservation_id: this.$route.params.reservation,
      paid: false,
      loading: true,
    }
  },

  methods: {
    fetchData() {
      axios.get('/reservationispaid/' + this.reservation_id)
          .then(response => {
            this.paid = response.data.data;
          })
          .finally(() => {
            this.loading = false;
          })
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>