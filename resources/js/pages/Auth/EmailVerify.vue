<template>
  <div class="flex justify-center items-center h-full w-full text-center">
    <div class="space-y-8">
      <div class="text-3xl" v-if="loading && !error">Momentje...</div>
      <div class="text-3xl" v-if="!loading && error">Oeps...</div>
      <div class="text-3xl" v-if="!loading && !error">Hallo!</div>
      <div v-if="loading && !error" class="flex gap-4">
        We verifieren je email... <i class="bx bx-loader-alt animate-spin"></i>
      </div>
      <div v-else-if="!loading && !error">
        <div class="flex">Je email is succesvol geverifieerd! <component :is="CheckIcon" class="h-5 w-5 text-green-500"></component></div>
        <div class="pt-4">
          <a href="/login" class="text-indigo-700">Inloggen &rarr;</a>
        </div>
      </div>
      <div v-else>
        <div class="">
          <div>Er is iets mis gegaan. Heb je de juiste verificatie token of is je email al geverifieerd?</div>
          <div class="pt-4">
            <a href="/login" class="text-indigo-700">Wil je inloggen? &rarr;</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {CheckBadgeIcon, CheckIcon} from "@heroicons/vue/16/solid/index.js";

export default {
  name: "EmailVerify",
  data() {
    return {
      loading: true,
      error: false,
    }
  },

  methods: {
    CheckIcon, CheckBadgeIcon,
    verifyEmail() {
      axios.post('/sanctum/email/verify', {
        user: this.$route.query.user,
        token: this.$route.query.token,
      })
          .then(response => {
            this.loading = false;
          })
          .catch(() => {
            this.error = true;
          })
    }
  },

  mounted() {
    this.verifyEmail();
  }

}
</script>