<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Producten</div>
        <div class="text-sm">Beheer hier de producten voor je onderneming</div>
      </div>
      <div>
        <button @click="$router.push({ name: 'venues.products.create' })" class="btn btn-primary">Nieuw product</button>
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
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Omschrijving</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Prijs per reservering</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Beschikbaar</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-for="product in products" :key="product.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer" @click="this.$router.push({name: 'venues.products.edit', params: {venue: this.$route.params.venue, product: product.id}})">
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">{{ product.name }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ product.description ?? '-' }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ $filters.currency(product.price) }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ product.is_active ? 'Ja' : 'Nee' }}</td>
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
      products: [],
    }
  },

  methods: {
    fetchData() {
      axios.get('/venues/' + this.$route.params.venue + '/products')
          .then(response => {
            this.products = response.data.data;
          })
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>