<template>
  <div v-if="venue" class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto"
           src="https://ultimatelemon.eu/_next/image?url=https%3A%2F%2Fcdn.ultimatelemon.eu%2Ftransparent-black-banner.png&w=256&q=75"
           alt="Your Company">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Registreer bij
        {{ venue.name }}</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <div class="text-center text-red-500 mb-4">{{ errors?.errors ? errors.errors[0] : '' }}</div>
      <div class="text-center text-green-500 mb-4" v-if="success">Verifieer je email, check ook je spam folder. <span
          class="text-black ml-2 cursor-pointer" @click="success = false"><i class="fa fa-close"></i></span></div>
      <div class="space-y-6">
        <div v-if="errors.data" class="rounded-md bg-yellow-50 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                      clip-rule="evenodd"/>
              </svg>
            </div>
            <h3 class="ml-3 text-sm font-medium text-yellow-800">{{ errors.data[0] }}</h3>
          </div>
        </div>

        <div>
          <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Naam</label>
          <div class="mt-2">
            <input v-model="formData.name" v-on:keyup.enter="register" type="text" autocomplete="name" required
                   :class="errors?.errors?.name ? 'border-1 border-red-500': ''"
                   class="block w-full border rounded-md  p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <span class="text-red-500 text-sm" v-if="errors?.errors?.name">{{ errors.errors.name[0] }}</span>
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
          <div class="mt-2">
            <input v-model="formData.email" v-on:keyup.enter="register" type="email" autocomplete="email" required
                   :class="errors?.errors?.email ? 'border-1 border-red-500': ''"
                   class="block w-full border rounded-md  p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <span class="text-red-500 text-sm" v-if="errors?.errors?.email">{{ errors.errors.email[0] }}</span>
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Wachtwoord</label>
            <div class="text-sm">
              <!--              <router-link :to='{name: "password.reset.request"}' class="font-semibold text-indigo-600 hover:text-indigo-500">Wachtwoord vergeten?</router-link>-->
            </div>
          </div>
          <div class="mt-2">
            <input v-model="formData.password" v-on:keyup.enter="register" type="password" autocomplete="password"
                   required :class="errors?.errors?.password ? 'border-1 border-red-500': ''"
                   class="block w-full border rounded-md  p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <span class="text-red-500 text-sm" v-if="errors?.errors?.password">{{ errors.errors.password[0] }}</span>
          </div>
        </div>

        <div>
          <button v-if="!loading" @click="register" type="submit"
                  class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Registreren
          </button>
          <button v-else type="submit"
                  class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
              <path
                  d="M12 22c5.421 0 10-4.579 10-10h-2c0 4.337-3.663 8-8 8s-8-3.663-8-8c0-4.336 3.663-8 8-8V2C6.579 2 2 6.58 2 12c0 5.421 4.579 10 10 10z"></path>
            </svg>
          </button>
        </div>
      </div>
      <p class="mt-10 text-center text-sm text-gray-500">
        Al een account bij {{ venue.name }}?
        <router-link :to="{name: 'application.login'}"
                     class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Log in
        </router-link>
      </p>
    </div>
  </div>
</template>

<script>

export default {
  name: "Register",
  data() {
    return {
      loading: false,
      errors: [],
      showResendButton: false,
      venue: null,
      success: false,

      formData: {
        name: null,
        email: null,
        password: null,
        subdomain: this.subdomain,
      }
    }
  },

  methods: {
    fetchVenue() {
      axios.get('/venue/' + this.subdomain)
          .then(response => {
            this.venue = response.data.data;
            if (new Date(response.data.data.stripe_current_period_ends_at) <= new Date()) this.activeSubscription = false;
            this.fetchProducts();
          })
    },

    register() {
      this.loading = true;
      axios.post('/app/sanctum/register', {
        name: this.formData.name,
        email: this.formData.email,
        password: this.formData.password,
        subdomain: this.subdomain,
      })
          .then(response => {
            this.errors = [];
            this.success = true;
            this.formData = {
              name: null,
              email: null,
              password: null,
              subdomain: this.subdomain,
            }
          })
          .catch(e => {
            this.errors = e.response.data
            this.success = false;
            if (e.response.data.errors.email_verification) this.showResendButton = true;
            console.log(e.response.data)
          })
          .finally(e => {
            this.loading = false;
          })
    },

    resendVerificationMail() {
      axios.post('/app/sanctum/email/verify/resend', {
        email: this.formData.email,
      })
          .then(response => {
            this.errors.errors.email_verification = 'Verificatie mail is opnieuw verzonden';
            this.showResendButton = false;
          })
    },

    check() {
      if (window.localStorage.getItem('tr_member_auth_token')) {
        window.location.href = '/dashboard';
      }
    }
  },

  mounted() {
    this.check();
    this.fetchVenue();
  },

  computed: {
    subdomain: function () {
      return window.location.hostname.split('.')[0]
    },
  },
}

</script>
