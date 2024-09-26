<template>
  <main>
    test
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
      navigation: [
        { name: 'Dashboard', href: '#', icon: HomeIcon, current: true },
        {
          name: 'Teams',
          icon: UsersIcon,
          current: false,
          children: [
            { name: 'Engineering', href: '#' },
            { name: 'Human Resources', href: '#' },
            { name: 'Customer Success', href: '#' },
          ],
        },
        {
          name: 'Projects',
          icon: FolderIcon,
          current: false,
          children: [
            { name: 'GraphQL API', href: '#' },
            { name: 'iOS App', href: '#' },
            { name: 'Android App', href: '#' },
            { name: 'New Customer Portal', href: '#' },
          ],
        },
        { name: 'Calendar', href: '#', icon: CalendarIcon, current: false },
        { name: 'Documents', href: '#', icon: DocumentDuplicateIcon, current: false },
        { name: 'Reports', href: '#', icon: ChartPieIcon, current: false },
      ],

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

    addVenue() {
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

    logout() {
      axios.post('/sanctum/logout')
          .then(() => {
            window.localStorage.removeItem('tr_auth_token');
            window.location.href = '/';
          })
    },

    openCustomerPortal() {
      alert('goed');
    }
  },

  mounted() {
    this.fetchUser();
    this.fetchPlans();
  },

  computed: {
    DateTime() {
      return DateTime
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