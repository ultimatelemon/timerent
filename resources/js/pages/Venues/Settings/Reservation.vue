<template>
  <div>
    <div class="font-semibold text-lg mb-4">Reserveringen</div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <div>
        <label for="prefix" class="block text-sm font-medium leading-6 text-gray-900">Reservering ID prefix <span
            class="required-star">*</span></label>
        <span class="text-xs">* Bijv: {{formData.prefix}}9C3F1A9B.</span>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.prefix" type="text" name="prefix" id="prefix"
                 v-on:keyup.enter="postData"
                 :class="errors.prefix ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="prefix-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.prefix" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.prefix" class="mt-2 text-sm text-red-600" id="prefix-error">{{ errors.prefix[0] }}</p>
      </div>

      <div>
        <label for="cancel_hours_before" class="block text-sm font-medium leading-6 text-gray-900">Annuleringstijd in uren <span
            class="required-star">*</span></label>
        <span class="text-xs">Maximaal verschil tot aan reservering om te annuleren.</span>
        <div class="relative mt-2 rounded-md shadow-sm">
          <input v-model="formData.cancel_hours_before" type="text" name="cancel_hours_before" id="cancel_hours_before"
                 v-on:keyup.enter="postData"
                 :class="errors.cancel_hours_before ? 'ring-red-300' : ''"
                 class="block w-full rounded-md border-0 py-1.5 pr-10 ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                 aria-invalid="true" aria-describedby="cancel_hours_before-error"/>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg v-if="errors.cancel_hours_before" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                    clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-if="errors.cancel_hours_before" class="mt-2 text-sm text-red-600" id="cancel_hours_before-error">{{ errors.cancel_hours_before[0] }}</p>
      </div>


    </div>
  </div>
</template>

<script>
export default {
  name: "Reservation",
  data() {
    return {
      errors: [],

      formData: {
        prefix: 'TR-',
        cancel_hours_before: 0,
      },
    }
  },

  methods: {
    fetchData() {
      axios.get('')
    },

    postData() {
     //
    }
  },
}
</script>