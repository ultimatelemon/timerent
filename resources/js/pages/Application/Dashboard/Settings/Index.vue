<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Instellingen</div>
        <div class="text-sm">Beheer hier je persoonlijke instellingen</div>
      </div>
      <div>
        <!--        <button @click="$router.push({ name: 'venues.units.create' })" class="btn btn-primary">Nieuwe unit</button>-->
      </div>
    </div>
    <div class="mb-12" v-if="saved">
      <div class="text-green-500">Je instellingen zijn opgeslagen! <i @click="saved = false" class="cursor-pointer fa fa-close text-black ml-3"></i></div>
    </div>
    <div class="mt-8 flow-root">
      <div class="space-y-8">
        <div>
          <label for='name'>Volledige naam <span class='text-red-500'>*</span></label>
          <div class='mt-2'>
            <input v-model='formData.name' type='text' name='name' autocomplete='name'
                   placeholder="Jan Petersen"
                   :class='errors?.name ? "ring-red-500" : ""'
                   class='block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'>
            <span class='text-red-500' v-if='errors?.name'>{{ errors?.name[0] }}</span>
          </div>
        </div>

        <div>
          <label for='email'>Email <span class='text-red-500'>*</span></label>
          <div class='mt-2'>
            <input v-model='formData.email' type='text' name='email' autocomplete='email'
                   placeholder="j.petersen@voorbeeld.com"
                   :class='errors?.email ? "ring-red-500" : ""'
                   class='block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'>
            <span class='text-red-500' v-if='errors?.email'>{{ errors?.email[0] }}</span>
          </div>
        </div>

        <div>
          <label for='phone_number'>Telefoonnummer <span class='text-red-500'>*</span></label>
          <div class='mt-2'>
            <input v-model='formData.phone_number' type='text' name='phone_number' autocomplete='phone_number'
                   placeholder="0612345678"
                   :class='errors?.phone_number ? "ring-red-500" : ""'
                   class='block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'>
            <span class='text-red-500' v-if='errors?.phone_number'>{{ errors?.phone_number[0] }}</span>
          </div>
        </div>
      </div>

      <div class="mt-8 flex justify-end">
        <button @click="postData" class="btn btn-lg"
                :class="(this.loading || !this.formData.name || !this.formData.email || !this.formData.phone_number  ? 'btn-secondary opacity-50 cursor-not-allowed' : 'btn-primary')">
          <i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Opslaan
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import Pagination from "../../../Components/Pagination.vue";

export default {
  name: "Index",
  components: {Pagination},
  data() {
    return {
      reservations: [],
      pagination: null,
      loading: false,
      errors: [],
      saved: false,

      formData: {
        name: null,
        email: null,
        phone_number: null,
      }
    }
  },

  methods: {
    fetchMember() {
      if (window.localStorage.getItem("tr_member_auth_token")) {
        axios.get('/app/members/current')
            .then(response => {
              this.formData.name = response.data.data.name;
              this.formData.email = response.data.data.email;
              this.formData.phone_number = response.data.data.phone_number;
            }).catch(() => {
          window.localStorage.removeItem("tr_member_auth_token");
        })
      }
    },

    postData() {
      if (this.loading || !this.formData.name || !this.formData.email || !this.formData.phone_number) return;
      this.loading = true;

      axios.post('/app/members/current/update', this.formData)
          .then(() => {
            this.saved = true;
            this.errors = [];
          })
          .catch(e => {
            this.errors = e.response.data.errors;
          })
          .finally(() => {
            this.loading = false;
          })
    }
  },

  mounted() {
    this.fetchMember();
  },

  computed: {
    subdomain: function () {
      return window.location.hostname.split('.')[0]
    },
  },
}
</script>