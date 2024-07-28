<template>
  <div v-if="venue">
    <div class="mb-12">
      <div class="font-semibold text-lg">{{ unit ? unit.name : 'Nieuwe unit' }}</div>
      <div class="text-sm">Beheer hier de unit</div>
      <p v-if="this.$route.params.unit == null && venue.unit_count >= venue.plan.unit_limit" class="text-red-500 pt-2"><b>Let op:</b> Je hebt op dit moment <b>{{venue.unit_count}}</b> units aangemaakt. Het limiet voor dit abonnement is <b>{{venue.plan.unit_limit}}</b>. Bij het aanmaken van een nieuwe unit worden er kosten in rekening gebracht.</p>
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
    </div>

    <modal v-if="showModal" @close="showModal = false">
      <div class="px-1 border-b border-gray-200 flex items-center justify-between">
        <p class="font-medium p-4">Nieuwe unit</p>
        <button class="p-4" @click="showModal = false"><i class="far fa-times"></i></button>
      </div>
      <div class="p-5">
        <div class="text-red-500">
          Letop: Er worden extra kosten in rekening gebracht. Weet je zeker dat je een nieuwe unit wilt aanmaken?
        </div>
      </div>
      <div class="flex justify-end bg-gray-50 px-5 py-3">
        <button :class="loading ? 'btn btn-secondary opacity-50 cursor-not-allowed btn-lg' : 'btn btn-lg btn-primary'" @click="postData"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i>Aanmaken</button>
      </div>
    </modal>

    <div class="flex justify-end gap-4">
      <button @click="$router.go(-1)" class="btn btn-secondary">Annuleren</button>
      <button v-if="this.$route.params.unit != null || venue.unit_count < venue.plan.unit_limit" :class="loading ? 'btn btn-secondary opacity-50 cursor-not-allowed btn-lg' : 'btn btn-lg btn-primary'" @click="postData"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i>Aanmaken</button>
      <button v-else @click="showModal = true;" class="btn btn-primary">Opslaan</button>
    </div>

  </div>
</template>

<script>
import Modal from "../../Components/Modal.vue";

export default {
  name: "Edit",
  components: {Modal},
  data() {
    return {
      // unit_id: this.$route.params.unit,
      loading: false,
      unit: null,
      errors: [],
      venue: null,
      showModal: false,

      formData: {
        name: "",
        description: "",
        tax_percentage: 0,
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
      if (this.$route.params.unit != null) {
        this.loading = true;
        axios.get('/venues/' + this.$route.params.venue + '/units/' + this.$route.params.unit)
            .then(response => {
              this.unit = response.data.data;
              this.formData.name = this.unit.name;
              this.formData.description = this.unit.description;
              this.formData.tax_percentage = this.unit.tax_percentage;
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    postData() {
      if(this.loading) return;
      this.loading = true;

      if (this.$route.params.unit != null) {
        axios.put('/venues/' + this.$route.params.venue + '/units/' + this.$route.params.unit, this.formData)
            .then(response => {
              this.$router.push({name: 'venues.units.index'});
            }).catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      } else {
        axios.post('/venues/' + this.$route.params.venue + '/units', this.formData)
            .then(response => {
              this.$router.push({name: 'venues.units.index'});
            }).catch(error => {
              this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      }
    }
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
  }
}
</script>
