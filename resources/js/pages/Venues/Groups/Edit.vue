<template>
  <div v-if="venue">
    <div class="mb-12">
      <div class="font-semibold text-lg">{{ group ? group.name : 'Nieuwe groep' }}</div>
      <div class="text-sm dark:text-slate-400">Beheer hier de rol</div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-12">
      <div>
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Naam <span
            class="required-star">*</span></label>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.name" type="text" name="name" id="name"
                 v-on:keyup.enter="postData"
                 :class="errors.name ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 input"
                 aria-invalid="true" aria-describedby="name-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.name" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor"
                 aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.name" class="mt-2 text-sm text-red-600" id="name-error">{{ errors.name[0] }}</p>
      </div>
    </div>

    <div class="flex justify-end gap-4">
      <button @click="$router.go(-1)" class="btn btn-secondary">Annuleren</button>
      <button :class="loading ? 'btn btn-secondary opacity-50 cursor-not-allowed btn-lg' : 'btn btn-lg btn-primary'"
              @click="postData"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i>Opslaan
      </button>
    </div>

  </div>
</template>

<script>
import Modal from "../../Components/Modals/Modal.vue";

export default {
  name: "Edit",
  components: {Modal},
  data() {
    return {
      loading: false,
      group: null,
      errors: [],
      venue: null,
      showModal: false,

      formData: {
        name: null,
        venue_id: this.$route.params.venue,
      },
    }
  },

  methods: {
    fetchVenue() {
      axios.get('/venues/' + this.$route.params.venue)
          .then(response => {
            this.venue = response.data.data;
          })
    },

    fetchData() {
      if (this.$route.params.group != null) {
        this.loading = true;
        axios.get('/venues/' + this.$route.params.venue + '/groups/' + this.$route.params.group)
            .then(response => {
              this.group = response.data.data;
              this.formData.name = this.group.name;
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    postData() {
      if (this.loading) return;
      this.loading = true;

      if (this.$route.params.group != null) {
        axios.put('/venues/' + this.$route.params.venue + '/groups/' + this.$route.params.group, this.formData)
            .then(response => {
              this.$router.push({name: 'venues.groups.index'});
            }).catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      } else {
        axios.post('/venues/' + this.$route.params.venue + '/groups', this.formData)
            .then(response => {
              this.$router.push({name: 'venues.groups.index'});
            }).catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      }
    },

  },

  mounted() {
    this.fetchVenue();
    this.fetchData();
  },

  computed: {
    current_venue: {
      get() {
        return this.$store.state.venue;
      }
    },

    current_user: {
      get() {
        return this.$store.state.user;
      }
    }
  },
}
</script>
