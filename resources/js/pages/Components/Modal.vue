<template>
  <div class="cover flex items-center justify-center fixed left-0 bottom-0 right-0 z-50 p-1 sm:p-5 md:p-10 overflow-y-auto noscrollbar" style="top: -50px;">
    <div class="fixed top-0 left-0 bottom-0 right-0" @click="$emit('close')"></div>
    <div class="window bg-white shadow-lg rounded-lg flex flex-col z-50 overflow-hidden" style="margin-top: 50px;">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "Modal"
}
</script>

<style scoped>
.cover { background: rgba(0, 0, 0, 0.4); }
.window { width: 100%; max-width: 750px; }
</style>