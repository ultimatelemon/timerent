<template>
  <main v-if="current_user">
    <div>
      <div class="mb-12 flex justify-between">
        <div>
          <div class="font-semibold text-lg">{{ this.greeting }}, {{current_user.name}}</div>
          <div class="text-sm">Beheer hier je persoonlijke account, maak een nieuwe vestiging of navigeer direct naar een bestaande vestiging.</div>
        </div>
      </div>
    </div>
  </main>
</template>


<script>

import Loader from "./Components/Loader.vue";
import Modal from "./Components/Modal.vue";
import {DateTime} from "luxon";
import {ArrowTopRightOnSquareIcon, HomeIcon, UsersIcon} from "@heroicons/vue/24/outline/index.js";
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import {
  Bars3Icon,
  BellIcon,
  XMarkIcon,
  ChevronRightIcon,
  FolderIcon,
  CalendarIcon,
  DocumentDuplicateIcon, ChartPieIcon
} from '@heroicons/vue/24/outline';

export default {
  name: "VenueSelector",
  components: {DisclosurePanel, Disclosure, DisclosureButton, MenuButton, MenuItem, MenuItems, Loader, Modal, Bars3Icon, BellIcon, XMarkIcon, ChevronRightIcon},
  data() {
    return {

      showNewVenue: false,
      user_venues: [],
      plans: [],

      errors: [],
      errorMessage: "",
      loading: false,
      current_user: null,
      current_user_role: null,
      subscription_url: null,

      name: "",
      subdomain: "",
      plan_id: null,
    }
  },

  methods: {
    ArrowTopRightOnSquareIcon,
    fetchUser() {
      axios.get('/users/current')
          .then(response => {
            this.current_user = response.data.data;
            this.current_user_role = response.data.role;
            this.fetchData();
          })
    },

    fetchData() {
      this.loading = true;

      axios.get('/users/' + this.current_user.id + '/user-venues?all=true')
          .then(response => {
            this.user_venues = response.data.data;
          })
          .catch(e => {
            console.log("ERROR", e)
          })
          .finally(() => {
            this.loading = false;
          })
    },

    storeVenue() {
      axios.post('/venues', {
        name: this.name,
        subdomain: this.subdomain.toLowerCase(),
        plan_id: this.plan_id,
      })
          .then(response => {
            this.name = "";
            this.showNewVenue = false;
            this.subscription_url = response.data.data;
          })
          .catch(error => {
            this.errors = error.response.data.errors;
            this.errorMessage = error.response.data.message;
            console.log(this.errors);
          })
    },

    openURL(url) {
      window.location.href = url
    },

    fetchPlans() {
      axios.get('/plans/available')
          .then(response => {
            this.plans = response.data.data;
          })
    },
  },

  mounted() {
    this.fetchUser();
    this.fetchPlans();
  },

  computed: {
    DateTime() {
      return DateTime
    },
    greeting() {
      const hour = DateTime.now().hour;
      if (hour >= 0 && hour < 6) return 'Goedenacht';
      if (hour >= 6 && hour < 12) return 'Goedemorgen';
      if (hour >= 12 && hour < 18) return 'Goedemiddag';
      if (hour >= 18 && hour < 24) return 'Goedenavond';
      return hour;
    },
    current_venue: {
      get() {
        return this.$store.state.venue;
      },
      set (value) {
        this.$store.commit('setVenue', value);
      }
    },

    // current_user: {
    //   get() {
    //     return this.$store.state.user;
    //   }
    // }
  }
}
</script>