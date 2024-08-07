<template>
  <div v-if='!success' class='h-screen flex justify-center items-center'>
    <div class='flex items-center justify-center gap-3'>
      <i class='bx bx-loader-alt animate-spin'></i> Bezig met afronden ...
    </div>
  </div>
  <div v-else class="text-center h-screen flex flex-col items-center justify-center">
    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
      <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
      </svg>
    </div>
    <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">Timerent Payments is nu ingeschakeld, je kunt nu betalingen ontvangen. 🥳</h1>
    <p class="mt-6 text-base leading-7 text-gray-600">Je kunt dit scherm nu sluiten.</p>
  </div>
</template>

<script>

export default {
  name: "Success",
  data() {
    return {
      venue: this.$route.query.v,
      response: null,

      success: false,
    }
  },

  methods: {
    fetchData() {
      axios.post('/venue/'+this.venue+'/payments/checkandupdate', {})
          .then(response => {
            this.response = response.data.data;
            this.success = true;
          })
          .then(e => {
            console.log(e.response.data)
          })
    }
  },

  mounted() {
    this.fetchData();
  },

  watch: {
    venue: function () {
      console.log("venue", this.venue)
      if(this.venue != null) this.fetchData();
    }
  }
}
</script>