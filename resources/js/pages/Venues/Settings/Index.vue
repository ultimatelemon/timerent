<template>
  <div>
    <div>
      <div class="font-semibold text-lg">Algemene instellingen</div>
      <div class="text-sm">Beheer hier de algemene instellingen voor je onderneming</div>
    </div>

    <div v-if="success" class="my-12 alert alert-success">
      <div>Je instellingen zijn opgeslagen</div>
      <i class="fa fa-close cursor-pointer" @click="success = false"></i>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mt-12">
      <div>
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900"> Bedrijfsnaam <span class="required-star">*</span></label>
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
        <label for="address" class="block text-sm font-medium leading-6 text-gray-900"> Adres <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.address" type="text" name="address" id="address"
                 v-on:keyup.enter="postData"
                 :class="errors.address ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.address" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.address" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.address[0] }}</p>
      </div>

      <div>
        <label for="postal_code" class="block text-sm font-medium leading-6 text-gray-900"> Postcode <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.postal_code" type="text" name="postal_code" id="postal_code"
                 v-on:keyup.enter="postData"
                 :class="errors.postal_code ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.postal_code" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.postal_code" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.postal_code[0] }}</p>
      </div>
      <div>
        <label for="city" class="block text-sm font-medium leading-6 text-gray-900"> Stad <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.city" type="text" name="city" id="city"
                 v-on:keyup.enter="postData"
                 :class="errors.city ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.city" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.city" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.city[0] }}</p>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium leading-6 text-gray-900"> Email <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.email" type="email" name="email" id="email"
                 v-on:keyup.enter="postData"
                 :class="errors.email ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.email" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.email" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.email[0] }}</p>
      </div>
      <div>
        <label for="phone_number" class="block text-sm font-medium leading-6 text-gray-900"> Telefoonnummer <span class="text-xs">(voor contact met Timerent)</span> <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.phone_number" type="text" name="phone_number" id="phone_number"
                 v-on:keyup.enter="postData"
                 :class="errors.phone_number ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.phone_number" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.phone_number" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.phone_number[0] }}</p>
      </div>

      <div>
        <label for="phone_number_support" class="block text-sm font-medium leading-6 text-gray-900"> Support Telefoonnummer <span class="text-xs">(zichtbaar voor klanten)</span> <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.phone_number_support" type="text" name="phone_number_support" id="phone_number_support"
                 v-on:keyup.enter="postData"
                 :class="errors.phone_number_support ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.phone_number_support" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.phone_number_support" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.phone_number_support[0] }}</p>
      </div>

      <div></div>

      <div>
        <label for="coc_number" class="block text-sm font-medium leading-6 text-gray-900"> KVK nummer <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.coc_number" type="text" name="coc_number" id="coc_number"
                 v-on:keyup.enter="postData"
                 :class="errors.coc_number ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.coc_number" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.coc_number" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.coc_number[0] }}</p>
      </div>
      <div>
        <label for="tax_number" class="block text-sm font-medium leading-6 text-gray-900"> BTW Nummer <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.tax_number" type="text" name="tax_number" id="tax_number"
                 v-on:keyup.enter="postData"
                 :class="errors.tax_number ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.tax_number" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.tax_number" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.tax_number[0] }}</p>
      </div>
    </div>
    <div class="mt-8 flex justify-end">
      <button @click="postData" class="btn btn-lg"
              :class="(this.loading ? 'btn-secondary opacity-50 cursor-not-allowed' : 'btn-primary')">
        <i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Opslaan
      </button>
    </div>
  </div>
</template>

<script>

export default {
  name: "Index",
  data() {
    return {

      loading: false,
      success: false,
      errors: [],

      formData: {
        name: null,
        address: null,
        postal_code: null,
        city: null,
        email: null,
        phone_number: null,
        phone_number_support: null,
        coc_number: null,
        tax_number: null,
      },
    }
  },

  methods: {
    postData() {
      if(this.loading) return;
      this.loading = true;

      axios.put('/venues/' + this.$route.params.venue + '/settings/general', this.formData)
          .then(response => {
            this.success = true;
            this.errors = [];
          })
          .catch(e => {
            this.errors = e.response.data.errors;
            this.success = false;
          })
          .finally(() => {
            this.loading = false;
          })
    },

    fetchData() {
      axios.get('/venues/' + this.$route.params.venue + '/settings/general')
          .then(response => {
            this.formData.name = response.data.data.name;
            this.formData.address = response.data.data.address;
            this.formData.postal_code = response.data.data.postal_code;
            this.formData.city = response.data.data.city;
            this.formData.email = response.data.data.email;
            this.formData.phone_number = response.data.data.phone_number;
            this.formData.phone_number_support = response.data.data.phone_number_support;
            this.formData.coc_number = response.data.data.coc_number;
            this.formData.tax_number = response.data.data.tax_number;
          })
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>