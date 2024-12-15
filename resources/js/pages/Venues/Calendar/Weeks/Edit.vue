<template>
  <div v-if="unit">
    <div class="mb-12">
      <div class="font-semibold text-lg">Week aanpassen voor "{{unit.name}}" in week {{formData.week}} van {{formData.year}}</div>
      <div class="text-sm dark:text-slate-400">Beheer hier een template voor een specifieke week.</div>
    </div>

    <div v-if="errors.invalid" class="alert alert-danger mb-12">
      <div>Template is ongeldig</div>
      <i class="fa fa-close cursor-pointer" @click="errors = []"></i>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
      <div>
        <label for="template_name" class="block text-sm font-medium leading-6 text-gray-900">Naam <span
            class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.template_name" type="text" name="template_name" id="template_name"
                 placeholder="Mijn nieuwe template"
                 v-on:keyup.enter="postData"
                 :class="errors.template_name ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 input"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.template_name" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.template_name" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.template_name[0] }}</p>
      </div>

      <div>
        <label for="interval" class="block text-sm font-medium leading-6 text-gray-900">Interval (per minuut) <span
            class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.interval" type="text" name="interval" id="interval"
                 placeholder="30"
                 v-on:keyup.enter="postData"
                 :class="errors.interval ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 input"
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
        <input-week-schedule :week-number="formData.week" :year="formData.year" v-model="formData.template"></input-week-schedule>
      </div>
    </div>


    <div class="flex justify-end gap-4">
      <div class="flex gap-4">
        <button @click="$router.go(-1)" class="btn btn-secondary">Annuleren</button>
        <button @click="postData" :class="loading ? 'btn-secondary' : 'btn-primary'" class="btn"><i v-if="loading" class="fa fa-spinner fa-spin mr-2"></i> Opslaan</button>
      </div>
    </div>
  </div>
</template>

<script>
import CurrencyInput from "../../../Components/CurrencyInput.vue";
import InputWeekSchedule from "../../../Components/InputWeekSchedule.vue";
import DeleteModal from "../../../Components/Modals/DeleteModal.vue";
import {DateTime} from "luxon";

export default {
  name: "Edit",
  components: {DeleteModal, InputWeekSchedule, CurrencyInput},
  data() {
    return {
      template: null,
      deleteModalOpen: false,
      loading: false,
      unit: null,
      errors: [],
      defaultTemplateId: "0a2f32e0-3002-4348-8576-1979b9905c2e",
      formData: {
        template_name: "",
        interval: 30,
        price: 0,
        template: null,
        year: null,
        week: null,
        unit_id: null,
      }
    }
  },

  methods: {
    fetchData() {
      this.loading = true;
      if(this.$route.params.week != null) {
        axios.get('/venues/' + this.$route.params.venue + '/weeks/' + this.$route.params.week)
            .then(response => {
              this.template = response.data.data;
              this.formData.template_name = response.data.data.template_name;
              this.formData.interval = response.data.data.interval;
              this.formData.price = response.data.data.price;
              this.formData.template = response.data.data.template;
              this.formData.year = response.data.data.year;
              this.formData.week = response.data.data.week;
              this.formData.unit_id = response.data.data.unit.id;
              this.unit = response.data.data.unit;
              console.log(response.data.data);
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
      if(this.$route.params.week != null) {
        axios.put('/venues/' + this.$route.params.venue + '/weeks/' + this.$route.params.week, this.formData)
            .then(response => {
              this.$router.push({ name: 'venues.calendar.index' });
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