<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Templates</div>
        <div class="text-sm">Beheer hier de templates voor je onderneming</div>
      </div>
      <div>
        <button @click="$router.push({ name: 'venues.templates.create' })" class="btn btn-primary">Nieuwe template</button>
      </div>
    </div>
    <!--    <div class="mb-12">-->
    <!--      <div class="font-semibold">Filteren</div>-->
    <!--    </div>-->
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
              <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Naam</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Interval (per minuut)</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Prijs per interval</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="templates.length >= 1" v-for="template in templates" :key="template.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer" @click="this.$router.push({name: 'venues.templates.edit', params: {venue: this.$route.params.venue, template: template.id}})">
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">{{ template.name }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ template.interval }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.currency(template.price) }}</td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="3" class="pt-12">Er zijn nog geen templates aangemaakt</td>
            </tr>
            </tbody>
          </table>
          <div v-if="pagination">
            <pagination :pagination="pagination" @changed="fetchDataByPage"></pagination>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from "../../Components/Pagination.vue";

export default {
  name: "Index",
  components: {Pagination},
  data() {
    return {
      templates: [],
      pagination: null,
    }
  },

  methods: {
    fetchDataByPage(page) {
      axios.get('/venues/' + this.$route.params.venue + '/templates?page='+page)
          .then(response => {
            this.templates = response.data.data;
            this.pagination = response.data.pagination;
          })
    }
  },

  mounted() {
    this.fetchDataByPage(1);
  },
}
</script>