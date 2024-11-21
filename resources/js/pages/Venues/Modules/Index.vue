<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Modules</div>
        <div class="text-sm">Bekijk hier al onze actieve modules</div>
      </div>
    </div>

    <div class="mt-8 flow-root">
      <div class="font-semibold mb-4">Boekhouding</div>
      <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div @click="addModule" v-for="module in modules" class="border-2 rounded-lg flex flex-col items-center justify-center py-8 hover:border-indigo-800 cursor-pointer">
          <img :src="module.image" class="mb-8" alt="">
          <h1>{{ module.name }}</h1>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Index",
  data() {
    return {
      modules: null,
    }
  },

  methods: {
    fetchData() {
      axios.get('/modules')
          .then(response => {
            this.modules = response.data.data;
          })
    },

    addModule() {
      const date = new Date();
      date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
      document.cookie = `module_venue_id=${this.$route.params.venue};expires=${date.toUTCString()};path=/;`

      window.location = 'http://timerent-rewrite.test/api/moneybird/auth';
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>