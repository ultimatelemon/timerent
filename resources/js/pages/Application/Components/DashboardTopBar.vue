<template>
  <div class="border-b mb-6 pb-3" v-if="member">
    <div class="py-6 flex justify-between items-center">
<!--      <a href="/" class="text-xl font-semibold cursor-pointer">{{ venue.name }}</a>-->
      <a class="text-sm" href="/dashboard">Ingelogd als, <b>{{member.name}}</b></a>
      <div class="space-x-4">
        <a href="/" class="btn btn-primary btn-lg">Terug</a>
        <button @click="logout" class="btn btn-danger btn-lg"><i class="fa fa-right-from-bracket"></i></button>
      </div>
    </div>
    <div class="flex gap-6 text-sm">
      <div>
        <router-link :to="{name: 'application.dashboard'}" active-class="border-indigo-500" class="border-b-2 hover:border-indigo-500 pb-1">Dashboard</router-link>
      </div>
      <div>
        <router-link :to="{name: 'application.reservations.index'}" active-class="border-indigo-500" class="border-b-2 hover:border-indigo-500 pb-1">Reserveringen</router-link>
      </div>
      <div>
        <router-link :to="{name: 'application.settings.index'}" active-class="border-indigo-500" class="border-b-2 hover:border-indigo-500 pb-1">Instellingen</router-link>
      </div>
<!--      <div>-->
<!--        <router-link :to="{name: 'application.dashboard'}" active-class="border-indigo-500" class="border-b-2 hover:border-indigo-500 pb-1">Dashboard</router-link>-->
<!--      </div>-->
    </div>
  </div>
</template>

<script>
export default {
  name: "DashboardTopBar",
  data() {
    return {
      member: null,
    }
  },

  methods: {
    fetchMember() {
      axios.get('/app/members/current')
          .then(response => {
            this.member = response.data.data;
          })
    },

    logout() {
      axios.post('/app/sanctum/logout')
          .then(response => {
            window.localStorage.removeItem('tr_member_auth_token');
            window.location.href = '/';
            console.log('logged out')
          })
    },
  },

  mounted() {
    this.fetchMember()
  }
}
</script>