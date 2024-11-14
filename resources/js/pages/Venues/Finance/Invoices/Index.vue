<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Facturen</div>
        <div class="text-sm">Beheer hier alle facturen</div>
      </div>
      <div>
        <!--        <button @click="$router.push({ name: 'venues.units.create' })" class="btn btn-primary">Nieuwe unit</button>-->
      </div>
    </div>
    <div class="mb-12">
      <div class="font-semibold mb-2">Filteren</div>
      <div class="grid grid-cols-3 gap-12">
        <div>
          <p>Zoeken</p>
          <input v-model="searchQuery" type="text" placeholder="Zoek op ID of email">
        </div>
        <div>
          <p>Status</p>
          <select name="paidStatus" id="paidStatus" v-model="paidStatus" class="input">
            <option :value="null">Alles</option>
            <option value="paid">Betaald</option>
            <option value="open">Niet betaald</option>
          </select>
        </div>
<!--        <div>-->
<!--          <p>Zoeken</p>-->
<!--          <input v-model="searchQuery" type="text" placeholder="Zoek op ID of email">-->
<!--        </div>-->
      </div>
    </div>
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
              <th scope="col"
                  class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">#ID
              </th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                Datum
              </th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                Email
              </th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                Totaalbedrag
              </th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                Waarvan BTW
              </th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">
                Betaalstatus
              </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="invoices.length > 0" v-for="invoice in invoices" :key="invoices.id"
                class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer">
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 font-semibold">
                #{{ invoice.number }}
              </td>
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500">
                {{ $filters.humanDate(invoice.created_at) }}
              </td>
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500">{{ invoice.email }}</td>
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500">
                {{ $filters.currency(invoice.payment_amount) }}
              </td>
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500">
                {{ $filters.currency(invoice.tax_high + invoice.tax_low) }}
              </td>
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500" v-if="invoice.paid_at">
                <span class="label label-success">Voldaan</span>
              </td>
              <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500" v-else>
                <span class="label label-danger">Niet voldaan</span>
              </td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="12" class="pt-12">Er zijn nog geen facturen</td>
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
import Pagination from "../../../Components/Pagination.vue";
import _ from "lodash";

export default {
  name: "Index",
  components: {Pagination},
  data() {
    return {
      invoices: [],
      pagination: null,
      paidStatus: null,
      searchQuery: "",
      searchMethod: _.debounce(() => {
        this.fetchDataByPage(1);
      }, 300),
    }
  },

  methods: {
    fetchDataByPage(page) {
      axios.get('/venues/' + this.$route.params.venue + '/invoices?page=' + page
          + (this.searchQuery ? '&q=' + this.searchQuery : '')
          + (this.paidStatus ? '&s=' + this.paidStatus : '')
      )
          .then(response => {
            this.invoices = response.data.data;
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
    },
    paidStatus: function () {
      this.fetchDataByPage(1)
    }
  }
}
</script>