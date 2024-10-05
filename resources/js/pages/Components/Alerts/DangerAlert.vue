<template>
  <div class="rounded-md bg-red-50 p-4 my-12">
    <div class="flex items-center">
      <div class="flex-shrink-0">
        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <!-- Circular background -->
          <circle cx="10" cy="10" r="8" fill="currentColor" class="text-red-400"/>

          <!-- Cross icon -->
          <path fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                d="M7 7l6 6M7 13L13 7"/>
        </svg>
      </div>
      <div class="ml-3 flex justify-between w-full items-center">
        <div class="text-sm font-medium text-red-800">
          <slot></slot>
        </div>
        <button @click="this.close" type="button"
                class="rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50">
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
  name: "DangerAlert",

  methods: {
    close() {
      this.$emit("close");
    }
  },
}
</script>