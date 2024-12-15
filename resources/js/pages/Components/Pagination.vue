<template>
  <div class="flex items-center w-full my-8" v-if="pagination">
    <button class="btn btn-lg dark:bg-slate-800" :class="current_page > 1 ? 'btn-primary' : 'btn-secondary text-gray-300 cursor-default'" @click="goToPage(current_page - 1)">
      <component :is="ArrowLeftIcon" class="h-5 w-5"></component>
    </button>
    <div class="mx-auto flex items-center">
      <p class="text-sm text-gray-800 dark:text-white">Page {{ current_page}} of {{ pagination.last_page }}</p>
      <p class="text-xs text-gray-500 ml-1.5 font-medium italic mr-auto dark:text-slate-400">({{ ((current_page - 1) * pagination.per_page) + 1 }} — {{ Math.min(current_page * pagination.per_page, pagination.total) }} of {{ pagination.total }})</p>
    </div>
    <button class="btn btn-lg dark:bg-slate-800" :class="current_page < pagination.last_page ? 'btn-primary' : 'btn-secondary text-gray-300 cursor-default'" @click="goToPage(current_page + 1)">
      <component :is="ArrowRightIcon" class="h-5 w-5"></component>
    </button>
  </div>
</template>

<script>
import {ArrowRightIcon, ArrowLeftIcon} from "@heroicons/vue/16/solid/index.js";

export default{
  name: "Pagination",
  props: ['pagination'],

  data() {
    return {
      current_page: null,
      last_page: null,
      lowest: 0,
      lowest_diff: 0,
      lowest_reached: false,
      highest: 1,
      highest_diff: 0,
      highest_reached: false,
    }
  },

  methods: {
    ArrowLeftIcon,
    ArrowRightIcon,
    fetchData() {
      this.current_page = this.pagination.current_page;
      this.last_page = this.pagination.last_page;

      this.lowest = Math.max(1, this.current_page - 3);
      this.lowest_diff = Math.min(3, this.current_page - this.lowest);
      this.lowest_reached = this.current_page <= 4;

      this.highest = this.pagination.last_page;
      this.highest_diff = Math.min(3, this.highest - this.current_page);
      this.highest_reached = (this.highest - this.current_page) <= 3;
    },

    goToPage(page) {
      if(page < 0 || page > this.highest) return;

      this.$emit('changed', page)
      return false;
    }
  },

  watch: {
    pagination: {
      handler(val) {
        this.fetchData();
      },
      deep: true,
    }
  },

  mounted() {
    this.fetchData()
  }
}
</script>
