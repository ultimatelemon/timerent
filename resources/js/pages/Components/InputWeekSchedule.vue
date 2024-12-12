<template>
  <div class="space-y-6">
    <div class="xl:flex justify-between border-b border-gray-900/10 pb-8" v-for="(template, i) in modelValue">
      <div class="w-full pb-5 xl:pb-0">{{ dayOfWeek(i) }}</div>
      <div class="w-full space-y-6">
        <div v-for="(range, j) in template.ranges" class="space-y-6">
          <div>
            <div class="flex gap-4">
              <flat-pickr
                  :config="config"
                  v-model="range.from"
                  class="block w-full rounded-md p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              />
              <span class="text-center pt-2">-</span>
              <flat-pickr
                  :config="config"
                  v-model="range.to"
                  class="block w-full rounded-md p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              />
              <button v-if="j === 0" :class='j !== 0 ? "hidden" : "hidden"' @click="addRangeToDay(template.ranges)" class="btn">
                <component :is="PlusIcon" class="icon-btn"></component>
              </button>
              <button v-else :class='j === 0 ? "invisible" : "visible"' @click="removeRangeFromDay(template.ranges, j)"
                      class="btn">
                <component :is="XMarkIcon" class="icon-btn-red"></component>
              </button>
            </div>
          </div>
        </div>

      </div>
      <div></div>
    </div>
  </div>
</template>

<script>

import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import {LockClosedIcon, PlusIcon, XMarkIcon} from "@heroicons/vue/16/solid/index.js";

export default {
  name: "InputWeekSchedule",
  props: ['modelValue'],
  data() {
    return {
      config: {
        noCalendar: true,
        enableTime: true,
        time_24hr: true,
        defaultHour: 9,
        "locale": {
          "firstDayOfWeek": 1 // start week on Monday
        }
      },
      monday: null,
    }
  },
  methods: {
    XMarkIcon,
    LockClosedIcon,
    PlusIcon,
    addRangeToDay(day) {
      // console.log(day)
      day.push({from: '', to: ''});
    },

    removeRangeFromDay(day, index) {
      day.splice(index, 1);
    },

    dayOfWeek(day) {
      return ['Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag', 'Zondag'][day];
    },

    saveData() {
      console.log("DATA", this.modelValue)
    }
  },
  watch: {
    modelValue: {
      handler(val) {
        this.$emit('updateTemplate', val);
        console.log('changed');
      },
      deep: true
    },
  },

  components: {
    flatPickr
  }

}
</script>