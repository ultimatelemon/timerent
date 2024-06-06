<template>
  <main class="fixed top-0 left-0 right-0 bottom-0 bg-gray-100 flex flex-col">
    <div v-if="current_user" class="leading-tight flex items-center p-5 rounded">
      <router-link to="#" class="cursor-pointer flex items-center mr-auto">
<!--        <img :src="current_user.avatar" :alt="current_user.name" class="w-12 h-12 rounded-full mr-5">-->
        <div class="leading-none">
          <h1 class="font-medium">{{ current_user.name }}</h1>
          <p class="text-gray-500 text-sm">{{ current_user.email }}</p>
        </div>
      </router-link>

      <button class="btn btn-primary" @click="logoutCurrentDevice">Logout</button>
    </div>
    <div class="px-5 flex flex-col items-center justify-center flex-1">
      <!--
          New venue modal
      -->
      <transition name="modalfade">
        <modal v-if="showNewVenue" @close="showNewVenue = false">
          <div class="px-1 border-b border-gray-200 flex items-center justify-between">
            <p class="font-medium p-4">Nieuwe venue</p>
            <button class="p-4" @click="showNewVenue = false"><i class="far fa-times"></i></button>
          </div>
          <div class="p-5">
            <div class="bg-blue-50 text-blue-400 p-4 rounded-md text-sm mb-5 flex items-center">
              <i class="far fa-info-circle mr-4"></i>
              <p>Registreer een nieuwe venue</p>
            </div>
            <label>Naam</label>
            <input v-model="name" type="text">
            <label>Subdomein</label>
            <input v-model="subdomain" type="text">
          </div>
          <div class="flex justify-end bg-gray-50 px-5 py-3">
            <button class="btn btn-primary ml-auto" @click="addVenue">
              Aanmaken
            </button>
          </div>
        </modal>
      </transition>

      <!--
          Content
      -->
      <div class="venue-wrap flex flex-wrap justify-center gap-4">
        <loader v-if="loading"></loader>
        <template v-for="user_venue in user_venues">
          <a @click="current_venue = user_venue.venue;" :href="'/store/' + user_venue.id + '/home'" class="cursor-pointer">
            <img v-if="user_venue.venue.avatar" class="w-32 h-32 rounded-md bg-gray-100 shadow border-8 border-white object-contain" :src="user_venue.venue.avatar.full_path"></img>
            <p v-else class="w-32 h-32 rounded-md bg-gray-100 shadow border-8 border-white flex items-center justify-center text-center">{{ user_venue.venue.name }}</p>
          </a>
        </template>
        <div class="w-32 h-32 rounded-md bg-gray-100 text-gray-500 shadow border-8 border-white font-bold cursor-pointer flex items-center justify-center text-center" @click="showNewVenue = true">
          <span class="text-3xl material-symbols-outlined">add</span>
        </div>
      </div>
    </div>
  </main>
</template>

<script>

import Loader from "./Components/Loader.vue";
import Modal from "./Components/Modal.vue";

export default {
  name: "VenueSelector",
  components: {Loader, Modal},
  data() {
    return {
      showNewVenue: false,
      user_venues: [],

      errors: [],
      loading: false,
      current_user: null,

      name: "",
      subdomain: "",
    }
  },

  methods: {
    fetchUser() {
      axios.get('/users/current')
          .then(response => {
            this.current_user = response.data.data;
            this.fetchData();
          })
    },

    fetchData() {
      this.loading = true;

      axios.get('/users/' + this.current_user.id + '/user-venues?all=true')
          .then(response => {
            this.user_venues = response.data.data;
          })
          .finally(() => {
            this.loading = false;
          })
    },

    addVenue() {
      axios.post('/venues', {name: this.name, subdomain: this.subdomain})
          .then(response => {
            this.name = "";
            this.showNewVenue = false;
            this.fetchData();
          })
          .catch(error => {
            this.errors = error.response.data.errors;
          })
    }
  },

  mounted() {
    this.fetchUser();
  },

  computed: {
    current_venue: {
      get() {
        return this.$store.state.venue;
      },
      set (value) {
        this.$store.commit('setVenue', value);
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