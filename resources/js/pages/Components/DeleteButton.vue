<template>
  <div>
    <div v-if="count">
      <button @click="this.delete" class="btn btn-danger leading-none w-12 h-8 flex items-center justify-center">
        <span>{{ count }}</span>
      </button>
    </div>
    <div v-else>
      <button @click="this.delete" class="btn btn-danger leading-none w-12 h-8 flex items-center justify-center">
<!--        <span class="material-symbols-outlined">delete</span>-->
        <i class="fa fa-trash"></i>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "DeleteButton",
  props: ['id'],
  data() {
    return {
      count: null
    }
  },
  methods: {
    delete() {
      if (!this.count) {
        return this.count = 3
      }

      if (this.count > 1) {
        return this.count--;
      }

      this.$emit('delete', this.id);
    }
  },
}
</script>

<style scoped>

</style>