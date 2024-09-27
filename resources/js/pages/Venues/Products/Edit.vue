<template>
  <div>
    <div class="mb-12">
      <div class="font-semibold text-lg">{{ product ? product.name : 'Nieuw product' }}</div>
      <div class="text-sm">Beheer hier het product</div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
      <div>
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Naam <span
            class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.name" type="text" name="name" id="name"
                 v-on:keyup.enter="postData"
                 :class="errors.name ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.name" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.name" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.name[0] }}</p>
      </div>

      <div>
        <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Omschrijving</label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.description" type="text" name="description" id="description"
                 v-on:keyup.enter="postData"
                 :class="errors.description ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="description-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.description" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.description" class="mt-2 text-sm text-red-600" id="description-error">{{ errors.description[0] }}</p>
      </div>

      <div>
        <label for="tax_percentage" class="block text-sm font-medium leading-6 text-gray-900">Belasting tarief <span class="required-star">*</span></label>
        <select v-model="formData.tax_percentage" id="location" name="location" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
          <option :value="0">0%</option>
          <option :value="9">9%</option>
          <option :value="21">21%</option>
        </select>
      </div>

      <div v-if="units">
        <label for="tax_percentage" class="block text-sm font-medium leading-6 text-gray-900">Product voor specifieke units?</label>
        <MultipleSelectUnits :selectedUnitsIds="formData.units.map(u => u.id)" :units="units" @selectionChange="updateSelectedUnits"></MultipleSelectUnits>
      </div>

      <div>
        <label for="price_per_timeblock" class="block text-sm font-medium leading-6 text-gray-900">Prijs per <span :class="formData.price_per_timeblock ? '' : 'font-semibold'">reservering</span>/<span :class="formData.price_per_timeblock ? 'font-semibold' : ''">tijdblock</span></label>
        <div class="relative mt-2">
          <Switch v-model="formData.price_per_timeblock" :class="[formData.price_per_timeblock ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">
            <span class="sr-only">Use setting</span>
            <span :class="[formData.price_per_timeblock ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']">
            <span :class="[formData.price_per_timeblock ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">
              <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
               <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
             </span>
            <span :class="[formData.price_per_timeblock ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">
             <svg class="h-3 w-3 text-indigo-600" fill="currentColor" viewBox="0 0 12 12">
               <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
             </svg>
            </span>
            </span>
          </Switch>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.price_per_timeblock" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.price_per_timeblock" class="mt-2 text-sm text-red-600" id="description-error">{{ errors.price_per_timeblock[0] }}</p>
      </div>

      <div>
        <label for="max_per_day" class="block text-sm font-medium leading-6 text-gray-900">Max aantal per dag te reserveren (0 voor geen limiet) <span
            class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.max_per_day" type="number" name="max_per_day" id="max_per_day"
                 v-on:keyup.enter="postData"
                 :class="errors.max_per_day ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.max_per_day" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.max_per_day" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.max_per_day[0] }}</p>
      </div>

      <div>
        <label for="price" class="block text-sm font-medium leading-6 text-gray-900">Prijs per {{ formData.price_per_timeblock ? 'tijdblock' : 'reservering' }}</label>
        <div class="relative mt-2">
          <CurrencyInput v-on:keyup.enter='postData' v-model='formData.price'></CurrencyInput>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.price" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.price" class="mt-2 text-sm text-red-600" id="description-error">{{ errors.price[0] }}</p>
      </div>

      <div>
        <label for="is_active" class="block text-sm font-medium leading-6 text-gray-900">Actief</label>
        <div class="relative mt-2">
          <Switch v-model="formData.is_active" :class="[formData.is_active ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">
            <span class="sr-only">Use setting</span>
            <span :class="[formData.is_active ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']">
            <span :class="[formData.is_active ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">
              <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
               <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
             </span>
            <span :class="[formData.is_active ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">
             <svg class="h-3 w-3 text-indigo-600" fill="currentColor" viewBox="0 0 12 12">
               <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
             </svg>
            </span>
            </span>
          </Switch>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.is_active" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.is_active" class="mt-2 text-sm text-red-600" id="description-error">{{ errors.is_active[0] }}</p>
      </div>

    </div>

    <div class="flex justify-between gap-4">
      <div>
        <delete-modal @delete="deleteData" @close="deleteModalOpen = false;" :show="deleteModalOpen"></delete-modal>
        <button v-if="product" @click="deleteModalOpen = true;" class="btn btn-danger">Verwijderen</button>
      </div>
      <div class="flex justify-between gap-4">
        <button @click="$router.go(-1)" class="btn btn-secondary">Annuleren</button>
        <button @click="postData" :class="loading ? 'btn-secondary' : 'btn-primary'" class="btn"><i v-if="loading" class="fa fa-spinner fa-spin mr-2"></i> Opslaan</button>
      </div>
    </div>
  </div>
</template>

<script>
import {Switch} from "@headlessui/vue";
import CurrencyInput from "../../Components/CurrencyInput.vue";
import MultipleSelectUnits from "../../Components/MultipleSelectUnits.vue";
import DeleteModal from "../../Components/Modals/DeleteModal.vue";

export default {
  name: "Edit",
  components: {DeleteModal, MultipleSelectUnits, CurrencyInput, Switch},
  data() {
    return {
      loading: false,
      product: null,
      errors: [],
      units: null,
      deleteModalOpen: false,

      formData: {
        name: "",
        description: "",
        price: 0,
        tax_percentage: 0,
        is_active: false,
        max_per_day: 0,
        price_per_timeblock: false,
        units: [],
      },
    }
  },

  methods: {
    fetchData() {
      if (this.$route.params.product != null) {
        this.loading = true;
        axios.get('/venues/' + this.$route.params.venue + '/products/' + this.$route.params.product)
            .then(response => {
              this.product = response.data.data;
              this.formData.name = this.product.name;
              this.formData.description = this.product.description;
              this.formData.price = this.product.price;
              this.formData.tax_percentage = this.product.tax_percentage;
              this.formData.is_active = this.product.is_active;
              this.formData.max_per_day = this.product.max_per_day;
              this.formData.price_per_timeblock = this.product.price_per_timeblock;
              this.formData.units = this.product.units;

              this.fetchUnits();
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    postData() {
      if(this.loading) return;
      this.loading = true;

      if (this.$route.params.product != null) {
        axios.put('/venues/' + this.$route.params.venue + '/products/' + this.$route.params.product, this.formData)
            .then(response => {
              this.$router.push({name: 'venues.products.index'});
            }).catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      } else {
        axios.post('/venues/' + this.$route.params.venue + '/products', this.formData)
            .then(response => {
              this.$router.push({name: 'venues.products.index'});
            }).catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      }
    },

    deleteData() {
      axios.delete('/venues/' + this.$route.params.venue + '/products/' + this.$route.params.product)
          .then(response => {
            this.$router.push({name: 'venues.products.index'});
          })
          .catch(e => {
            console.log(e.response.data);
          })
    },

    fetchUnits() {
      axios.get('/venues/' + this.$route.params.venue + '/units')
          .then(response => {
            this.units = response.data.data;
          })
    },

    updateSelectedUnits(value) {
      this.formData.units = value.map(u => u.id);
    },

  },

  mounted() {
    this.fetchData();
  },
}
</script>
