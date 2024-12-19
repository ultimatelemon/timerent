<template>
  <div class="rounded-md bg-green-50 dark:bg-green-400 p-4 my-12">
    <div class="flex items-center">
      <div class="flex-shrink-0">
        <svg class="h-5 w-5 text-green-400 dark:text-green-800" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                clip-rule="evenodd"/>
        </svg>
      </div>
      <div class="ml-3 flex justify-between w-full items-center">
        <div class="text-sm font-medium text-green-800 dark:text-green-900">
          <slot></slot>
        </div>
        <button @click="this.close" type="button"
                class="rounded-md bg-green-50 dark:bg-green-700 p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
          <span class="sr-only">Dismiss</span>
          <svg class="h-5 w-5 flex" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path
                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SuccessAlert",

  methods: {
    close() {
      this.$emit("close");
    }
  },
}
</script>