<template>
  <div>
    <div class="mb-12">
      <div class="font-semibold text-lg">Medewerkers</div>
      <div class="text-sm">Beheer hier medewerkers die zijn gekoppeld aan je venue.</div>
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
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Gebruikersrol</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-for="user in users" :key="user.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer">
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ user.name }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ user.email }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ user.role.name }}</td>
            </tr>
            </tbody>
          </table>
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
      users: [],

      pagination: null,
    }
  },

  methods: {
    fetchData() {
      axios.get('/venues/' + this.$route.params.venue + '/users')
          .then(response => {
            this.users = response.data.data;
            this.pagination = response.data.pagination;
          })
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>