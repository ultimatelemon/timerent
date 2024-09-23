<template>
  <div class="text-center h-screen flex flex-col items-center justify-center">
    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
      <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
      </svg>
    </div>
    <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">Deze pagina is vrij of momenteel niet beschikbaar.</h1>
    <a href="https://timerent.nl" class="mt-6 text-base leading-7 text-gray-600 flex items-center">Naar Timerent.nl &rarr;</a>
  </div>
</template>

<script>
export default {
  name: "PageNotAvailable"
}
</script>