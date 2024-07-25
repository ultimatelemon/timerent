<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Members</div>
        <div class="text-sm">Beheer hier je klanten die geregistreerd zijn op jouw platform</div>
      </div>
      <div>
        <button class="btn btn-primary btn-lg"><i class="fa fa-plus"></i></button>
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
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Naam</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Loyality points</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Betaald op factuur</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="members.length > 0" v-for="member in members" :key="member.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer" @click="this.$router.push({name: 'venues.members.edit', params: {member: member.id, venue: this.$route.params.venue}})">
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ member.name }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ member.email }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ member.loyality_points }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ member.pay_on_invoice ? 'Ja' : 'Nee' }}</td>
            </tr>
            <tr v-else>
              <td class="text-center py-24" colspan="12">
                Er zijn (nog) geen members geregistreerd via je platform.
              </td>
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
      members: [],

      pagination: null,
    }
  },

  methods: {
    fetchDataByPage(page) {
      axios.get('/venues/' + this.$route.params.venue + '/members?page=' + page)
          .then(response => {
            this.members = response.data.data;
            // alert(response.data.data[0].name)
            this.pagination = response.data.pagination;
          })
    }
  },

  mounted() {
    this.fetchDataByPage(1);
  },
}
</script>