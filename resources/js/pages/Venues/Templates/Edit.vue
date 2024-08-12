<template>
  <div>
    <div class="mb-12">
      <div class="font-semibold text-lg">{{ template && template.id !== this.defaultTemplateId ? template.name : 'Nieuwe template' }}</div>
      <div class="text-sm">Beheer hier je template</div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
      <div>
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Naam <span
            class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.name" type="text" name="name" id="name"
                 placeholder="Mijn nieuwe template"
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
        <label for="interval" class="block text-sm font-medium leading-6 text-gray-900">Interval (per minuut) <span
            class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.interval" type="text" name="interval" id="interval"
                 placeholder="30"
                 v-on:keyup.enter="postData"
                 :class="errors.interval ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="interval-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.interval" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.interval" class="mt-2 text-sm text-red-600" id="interval-error">{{ errors.interval[0] }}</p>
      </div>

      <div>
        <label for="price" class="block text-sm font-medium leading-6 text-gray-900">Prijs <span
            class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <CurrencyInput v-on:keyup.enter='postData' v-model='formData.price'></CurrencyInput>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.price" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.price" class="mt-2 text-sm text-red-600" id="price-error">{{ errors.price[0] }}</p>
      </div>
    </div>

    <div class="pb-4">
      <h2 class="text-base font-semibold leading-7 text-gray-900">Template instellingen</h2>
      <div class="mt-10">
        <input-week-schedule v-model="formData.template"></input-week-schedule>
      </div>
    </div>

    <div class="flex justify-end gap-4">
      <button @click="$router.go(-1)" class="btn btn-secondary">Annuleren</button>
      <button @click="postData" class="btn btn-primary">Opslaan</button>
    </div>
  </div>
</template>

<script>
import CurrencyInput from "../../Components/CurrencyInput.vue";
import InputWeekSchedule from "../../Components/InputWeekSchedule.vue";

export default {
  name: "Edit",
  components: {InputWeekSchedule, CurrencyInput},
  data() {
    return {
      template: null,
      loading: false,
      errors: [],
      defaultTemplateId: "0a2f32e0-3002-4348-8576-1979b9905c2e",
      formData: {
        name: "",
        interval: 30,
        price: 0,
        template: null,
      }
    }
  },

  methods: {
    fetchData() {
      this.loading = true;
      if(this.$route.params.template != null) {
        axios.get('/venues/' + this.$route.params.venue + '/templates/' + this.$route.params.template)
            .then(response => {
              this.template = response.data.data;
              this.formData.name = response.data.data.name;
              this.formData.interval = response.data.data.interval;
              this.formData.price = response.data.data.price;
              this.formData.template = response.data.data.template;
            })
            .finally(() => {
              this.loading = false;
            })
      } else {
        axios.get('/venues/' + this.$route.params.venue + '/templates/' + this.defaultTemplateId)
            .then(response => {
              this.template = response.data.data;
              this.formData.template = response.data.data.template;
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    postData() {
      if(this.loading) return;

      this.loading = true;
      if(this.$route.params.template != null) {
        axios.put('/venues/' + this.$route.params.venue + '/templates/' + this.$route.params.template, this.formData)
            .then(response => {
              this.$router.push({ name: 'venues.templates.index' });
            })
            .catch(error => {
              this.errors = error.response.data.errors;
            })
            .finally(() => {
              this.loading = false;
            })
      } else {
        axios.post('/venues/' + this.$route.params.venue + '/templates', this.formData)
            .then(response => {
              this.$router.push({ name: 'venues.templates.index' });
            })
            .catch(error => {
              this.errors = error.response.data.errors;
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    updateTemplate(template) {
      this.formData.template = template;
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>