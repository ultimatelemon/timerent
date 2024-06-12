<template>
  <div class="inline-block relative">
    <button :disabled="!timeblock['available']"
            @click='toggleSelect(unit.id, timeblock)'
            :class="(isSelected > 0 ? 'bg-indigo-500 text-white' : '') || (!timeblock['available'] ? 'opacity-25 cursor-not-allowed' : '')"
            class='border border-indigo-500 py-2 px-5 rounded mr-2 mb-3'>
      {{ timeblock['from'] }}
    </button>
  </div>
</template>

<script>
export default {
  name: "ButtonTimeReservation",
  props: ['to', 'unit', 'timeblock', 'selected'],

  methods: {
    toggleSelect(unit_id, timeblock) {
      let bookObject = {
        unit_id,
        timeblock,
      };

      console.log(bookObject)

      let list = this.selected;
      if (this.selected.filter(x => JSON.stringify(x) === JSON.stringify(bookObject)).length) {
        list = this.selected.filter(x => !(JSON.stringify(x) === JSON.stringify(bookObject)));
      } else {
        list.push(bookObject);
      }
      this.$emit('updateList', list);
    }
  },

  watch: {
    value: {
      handler(value) {
        this.$emit('button', value);
      },
      deep: true
    }
  },

  computed: {
    isSelected() {
      return Object.keys(this.selected).length > 0 &&
          this.selected.filter(x => JSON.stringify(x) === JSON.stringify({
            unit_id: this.unit.id,
            timeblock: this.timeblock
          })).length;
    }
  }
}
</script>