<template>
  <div>
    <div class="font-semibold text-lg mb-4">Reserveringen</div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <div v-for="setting in settings">
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">{{ setting.display_name }} <span
            v-if="setting.required"
            class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="setting.value" type="text" name="name" id="name"
                 v-if="setting.type === 'text'"
                 v-on:keyup.enter="postData"
                 :class="errors.name ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="name-error"/>

          <input v-model="setting.value" type="text" name="name" id="name"
                 v-if="setting.type === 'number'"
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
    </div>

    <div class="mt-12 flex justify-end gap-4">
      <button @click="postData" class="btn btn-lg btn-secondary">Instellingen opslaan</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "Reservation",
  data() {
    return {
      errors: [],
      settings: [],

      formData: {
        prefix: 'TR-',
        cancel_hours_before: 0,
      },
    }
  },

  methods: {
    fetchData() {
      axios.get('/venues/' + this.$route.params.venue + '/settings/category', {
        params: {
          'category': 'reservations',
        }
      })
          .then(response => {
            this.formData.name = response.data.data.name;
            this.formData.address = response.data.data.address;
            this.settings = response.data.data;
            console.log(response.data.data)
          })
    },

    postData() {
      axios.put('/venues/' + this.$route.params.venue + '/settings', {settings: this.settings})
          .then(response => {
            alert('Instellingen opgeslagen')
          })
          .catch(e => {
            alert('Er is een onbekende fout opgetreden');
            console.log(e.response.data)
          })
    }
  },

  mounted() {
    this.fetchData();
  }
}
</script>