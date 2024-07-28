<template>
  <div v-if="venue">
    <div class="mt-12 flex justify-between">
      <a href="/" class="text-xl font-semibold cursor-pointer">{{ venue.name }}</a>
      <a v-if="!member" href="/login" class="cursor-pointer">Mijn account</a>
      <a v-else href="/dashboard">{{member.name}}</a>
    </div>
  </div>
</template>

<script>
export default {
  name: "TopBar",
  data() {
    return {
      member: null,
      venue: null,
    }
  },

  methods: {
    fetchVenue() {
      axios.get('/venue/' + this.subdomain)
          .then(response => {
            this.venue = response.data.data;
          })
    },

    fetchMember() {
     if(window.localStorage.getItem("tr_member_auth_token")){
       axios.get('/app/members/current')
           .then(response => {
             if(response.data.data) this.member = response.data.data;
           }).catch(() => {
             window.localStorage.removeItem("tr_member_auth_token");
       })
     }
    }
  },

  mounted() {
    this.fetchVenue();
    this.fetchMember();
  },

  computed: {
    subdomain: function() {
      return window.location.hostname.split('.')[0]
    },
  },
}
</script>