<template>
  <div v-if="venue">
    <div class="mb-12">
      <div class="font-semibold text-lg">{{ role ? role.name : 'Nieuwe rol' }}</div>
      <div class="text-sm">Beheer hier de rol</div>
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

    <div class="my-10">
      <div class="grid grid-cols-2 gap-x-48">
        <div class="font-semibold">Bekijken</div>
        <div class="font-semibold">Beheren</div>
        <div v-for="item in permissions" class=" py-3">
          <div class="flex justify-between border-b pb-3">
            <label for="">{{item.flag}}</label>
            <input type="checkbox" class="ml-4" v-model="item.state">
          </div>
        </div>
      </div>
    </div>

    <!--    <div class="mt-10">-->
    <!--      <div class="flex justify-between gap-x-12">-->
    <!--        <div class="space-y-8 mr-24">-->
    <!--          <div class="font-bold">Rechten</div>-->
    <!--          <div v-for="item in permFlags">{{item}}</div>-->
    <!--        </div>-->
    <!--        <div class="grid grid-cols-2 gap-x-6 gap-y-4 w-full ">-->
    <!--&lt;!&ndash;          Gap y weg ?&ndash;&gt;-->
    <!--          <div class="font-bold">Bekijken</div>-->
    <!--          <div class="font-bold">Beheren</div>-->
    <!--          <div v-for="item in permissions" class="sm:col-span-1">-->
    <!--            <div class="">-->
    <!--              <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"-->
    <!--                     v-model="item.state">-->
    <!--            </div>-->
    <!--          </div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->

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
      role: null,
      errors: [],
      venue: null,
      showModal: false,

      formData: {
        name: null,
        bitfield: null,
      },

      permFlags: ['Units', 'Templates', 'Agenda', 'Producten', 'Reserveringen', 'Instellingen', 'Rapportage', 'Facturen', 'Medewerkers', 'Members'],

      permissions: {
        view_units: {flag: 'View units', state: false, binary: 1 << 1},
        manage_units: {flag: 'Manage units', state: false, binary: 1 << 2},
        view_templates: {flag: 'View templates', state: false, binary: 1 << 3},
        manage_templates: {flag: 'Manage templates', state: false, binary: 1 << 4},
        view_agenda: {flag: 'View agenda', state: false, binary: 1 << 5},
        manage_agenda: {flag: 'Manage agenda', state: false, binary: 1 << 6},
        view_products: {flag: 'View products', state: false, binary: 1 << 7},
        manage_products: {flag: 'Manage products', state: false, binary: 1 << 8},
        view_reservations: {flag: 'View reservations', state: false, binary: 1 << 9},
        manage_reservations: {flag: 'Manage reservations', state: false, binary: 1 << 10},
        view_settings: {flag: 'View settings', state: false, binary: 1 << 11},
        manage_settings: {flag: 'Manage settings', state: false, binary: 1 << 12},
        view_reports: {flag: 'View reports', state: false, binary: 1 << 13},
        manage_reports: {flag: 'Manage reports', state: false, binary: 1 << 14},
        view_invoices: {flag: 'View invoices', state: false, binary: 1 << 15},
        manage_invoices: {flag: 'Manage invoices', state: false, binary: 1 << 16},
        view_employees: {flag: 'View employees', state: false, binary: 1 << 17},
        manage_employees: {flag: 'Manage employees', state: false, binary: 1 << 18},
        view_members: {flag: 'View members', state: false, binary: 1 << 19},
        manage_members: {flag: 'Manage members', state: false, binary: 1 << 20},
        view_roles: {flag: 'View roles', state: false, binary: 1 << 21},
        manage_roles: {flag: 'Manage roles', state: false, binary: 1 << 22},
        view_groups: {flag: 'View groups', state: false, binary: 1 << 23},
        manage_Groups: {flag: 'Manage groups', state: false, binary: 1 << 24},
      }
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
      if (this.$route.params.role != null) {
        this.loading = true;
        axios.get('/venues/' + this.$route.params.venue + '/roles/' + this.$route.params.role)
            .then(response => {
              this.role = response.data.data;
              this.formData.name = this.role.name;
              this.formData.bitfield = this.role.bitfield;
              this.binaryToPermission(this.role.bitfield);
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    postData() {
      if (this.loading) return;
      this.loading = true;

      if (this.$route.params.role != null) {
        axios.put('/venues/' + this.$route.params.venue + '/roles/' + this.$route.params.role, this.formData)
            .then(response => {
              this.$router.push({name: 'venues.roles.index'});
            }).catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      } else {
        axios.post('/venues/' + this.$route.params.venue + '/roles', this.formData)
            .then(response => {
              this.$router.push({name: 'venues.roles.index'});
            }).catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      }
    },

    binaryToPermission(binary) {
      let permissions = this.permissions;

      for(const item in permissions) {
        if((binary & permissions[item].binary) !== 0) {
          permissions[item].state = true;
        }
      }
    },

    permissionsToBinary() {
      let permissions = this.permissions;
      let result = 0;

      for(const item in permissions) {
        if(permissions[item].state === true) {
          result += permissions[item].binary;
        }
      }
      this.formData.bitfield = result;
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
  },

  watch: {
    permissions: {
      handler: function() {
        console.log(this.permissions);
        this.permissionsToBinary();
      },
      deep: true,
    }
  }
}
</script>
