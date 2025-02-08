<template>
  <div v-if="user">
    <div class="mb-12">
      <div class="font-semibold text-lg">Mijn instellingen</div>
      <div class="text-sm dark:text-slate-400">Beheer hier je persoonlijke instellingen</div>
    </div>

    <SuccessAlert v-if="saved" @close="saved = false;">Je persoonlijke instellingen zijn opgeslagen</SuccessAlert>

    <div class="mb-8 text-sm">
      <span class="font-semibold">Let op! </span>Er wordt op dit moment nog <span class="text-red-500 underline">geen</span> verificatiemail verzonden naar een nieuw e-mailadres. Controleer je nieuwe email dus goed voor je deze aanpast.
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
      <div>
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Naam <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-md">
          <input v-model="formData.name" type="text" name="name" id="name"
                 v-on:keyup.enter="postData"
                 :class="errors.name ? 'ring-red-300' : ''"
                 class="input"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.name" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
      </div>
      <div>
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Email <span class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-md">
          <input v-model="formData.email" type="email" name="email" id="email"
                 v-on:keyup.enter="postData"
                 :class="errors.email ? 'ring-red-300' : ''"
                 class="input"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.email" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <div class="flex justify-end gap-4">
      <div class="flex gap-4">
        <button @click="$router.go(-1)" class="btn btn-secondary">Annuleren</button>
        <button @click="postData" class="btn btn-primary" :class="loading ? 'btn btn-secondary cursor-not-allowed' : 'btn btn-primary'"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Opslaan</button>
      </div>
    </div>

  </div>
</template>

<script>
import DeleteModal from "../Components/Modals/DeleteModal.vue";
import Success from "../StripeConnectSuccess.vue";
import SuccessAlert from "../Components/Alerts/SuccessAlert.vue";

export default {
  name: "Settings",
  components: {SuccessAlert, Success, DeleteModal},
  data() {
    return {
      formData: {
        name: null,
        email: null,
      },
      user: null,
      errors: [],
      loading: false,
      saved: false,
    }
  },

  methods: {
    fetchUser() {
     axios.get('/users/current')
         .then(response => {
           this.user = response.data.data;
           this.formData.name = response.data.data.name;
           this.formData.email = response.data.data.email;
         })
    },

    postData() {
      if(this.loading) return;
      this.loading = true;
      axios.post('/users/current/update', this.formData)
          .then(response => {
            this.user = response.data.data;
            this.saved = true;
            this.errors = [];
          })
          .catch(e => {
            this.errors = e.response.data.errors;
            this.saved = false;
          })
          .finally(() => {
            this.loading = false;
          })
    },
  },

  mounted() {
    this.fetchUser();
  },
}
</script>