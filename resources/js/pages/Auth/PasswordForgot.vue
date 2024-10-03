<template>
  <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto" src="https://ultimatelemon.eu/_next/image?url=https%3A%2F%2Fcdn.ultimatelemon.eu%2Ftransparent-black-banner.png&w=256&q=75" alt="Your Company">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Wachtwoord vergeten</h2>
    </div>


    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <div class="text-center text-red-500 mb-4">{{errors?.errors ? errors.errors[0] : ''}}</div>

      <div class="alert alert-success text-sm mb-8" v-if="success">
        <p>Je reset verzoek is verzonden</p>
        <i @click="success = false;" class="fa fa-close cursor-pointer"></i>
      </div>

      <div class="space-y-6">
        <div v-if="errors.data" class="rounded-md bg-yellow-50 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
            </div>
            <h3 class="ml-3 text-sm font-medium text-yellow-800">{{ errors.data[0] }}</h3>
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
          <div class="mt-2">
            <input v-model="formData.email" v-on:keyup.enter="postData" type="email" autocomplete="email" required :class="errors?.errors?.email ? 'border-1 border-red-500': ''" class="block w-full border rounded-md  p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <span class="text-red-500 text-sm" v-if="errors?.errors?.email">{{ errors.errors.email[0]  }}</span>
          </div>
        </div>

        <div>
          <button v-if="!loading" @click="postData" type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Stuur reset link</button>
          <button v-else type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;"><path d="M12 22c5.421 0 10-4.579 10-10h-2c0 4.337-3.663 8-8 8s-8-3.663-8-8c0-4.336 3.663-8 8-8V2C6.579 2 2 6.58 2 12c0 5.421 4.579 10 10 10z"></path></svg>
          </button>
        </div>
      </div>
      <p class="mt-10 text-center text-sm text-gray-500">
        Weet je je wachtwoord weer?
        <router-link :to="{name: 'login'}" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Log in</router-link>
      </p>
    </div>
  </div>
</template>

<script>

export default {
  name: "Login",
  data() {
    return {
      loading: false,
      errors: [],
      success: false,

      formData: {
        email: null,
      }
    }
  },

  methods: {

    postData() {
      this.loading = true;
      axios.post('/sanctum/password/reset/request', {
        email: this.formData.email,
      })
          .then(response => {
            this.success = true;
          })
          .catch(e => {
            this.errors = e.response.data
            this.success = false;
            console.log(e.response.data)
          })
          .finally(e => {
            this.loading = false;
          })
    },

    check() {
      if (window.localStorage.getItem('tr_auth_token')) {
        window.location.href = '/';
      }
    }
  },

  mounted() {
    // this.check();
  },

  computed: {

  },
}

</script>
