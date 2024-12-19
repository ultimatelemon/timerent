<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Member groepen</div>
        <div class="text-sm dark:text-slate-400">Beheer hier de groepen voor je members</div>
      </div>
      <div>
        <button @click="$router.push({ name: 'venues.groups.create' })" class="btn btn-primary">Nieuwe groep</button>
      </div>
    </div>
    <div class="mb-12">
      <div class="font-semibold mb-2">Filteren</div>
      <input class="input" v-model="searchQuery" type="text" placeholder="Zoek op naam">
    </div>
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
              <th scope="col" class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Naam</th>
<!--              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Omschrijving</th>-->
<!--              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">BTW Tarief</th>-->
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="groups.length >= 1" v-for="group in groups" :key="group.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer" @click="this.$router.push({name: 'venues.groups.edit', params: {venue: this.$route.params.venue, group: group.id}})">
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">{{ group.name }}</td>
<!--              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ unit.tax_percentage }}%</td>-->
            </tr>
            <tr v-else class="text-center">
              <td colspan="3" class="py-12">Er zijn nog geen groepen aangemaakt of zoekopdracht niet gevonden</td>
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
import _ from "lodash";

export default {
  name: "Index",
  components: {Pagination},
  data() {
    return {
      groups: [],
      pagination: null,

      searchQuery: "",
      searchMethod: _.debounce(() => {
        this.fetchDataByPage(1);
      }, 300),
    }
  },

  methods: {
    fetchDataByPage(page) {
      axios.get('/venues/' + this.$route.params.venue + '/groups?page=' + page
          + (this.searchQuery ? '&q=' + this.searchQuery : '')
      )
          .then(response => {
            this.groups = response.data.data;
            this.pagination = response.data.pagination;
          })
    }
  },

  mounted() {
    this.fetchDataByPage(1);
  },

  watch: {
    searchQuery: function () {
      this.searchMethod();
    }
  }
}
</script>