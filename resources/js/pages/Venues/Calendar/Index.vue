<template>
  <div>
    <div class="mb-12 lg:flex justify-between">
      <div>
        <div class="font-semibold text-lg">Agenda</div>
        <div class="text-sm dark:text-slate-400">Wijs hier per week een template toe aan een unit</div>
      </div>
      <div class="flex items-center gap-5 mt-4 lg:mt-0">
        <component @click="year--" :is="MinusIcon" class="icon-btn"></component>
        {{ year }}
        <component @click="year++" :is="PlusIcon" class="icon-btn"></component>
      </div>
      <div class="mt-4 lg:mt-0">
        <a @click='year = DateTime.now().year'
           :href='"#" + (DateTime.now().weekNumber === 1 ? "1" : (DateTime.now().weekNumber -1))'>Naar huidige week</a>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
      <div class="bg-gray-100 dark:bg-slate-800 rounded p-4 w-full" v-for="i in 52" :id="i"
           :class="DateTime.now().weekNumber === i && DateTime.now().year === year ? 'border-2 border-indigo-700' : ''">
        <div><span class="font-semibold">Week: {{ i }}</span> <span class="text-sm text-gray-600 dark:text-slate-400">({{ firstAndLastDayOfWeek(i) }})</span></div>

        <Disclosure>
          <DisclosureButton class="py-2 flex items-center gap-2">
            <span>{{(units.length > 0 ? 'Bekijk units' : 'Maak eerst een unit aan')}}</span>
            <component v-if="units.length > 0" :is="ArrowRightIcon" class="h-5 w-5"></component>
          </DisclosureButton>
          <DisclosurePanel class="text-gray-500">
            <div v-for="unit in units" class="space-y-2">
              <div
                  @click="openModal(i, unit)"
                  :class="weeks.find(x => x.week === i && x.unit.id === unit.id && x.year === year) ? 'border-green-500 text-black dark:bg-slate-700' : 'border-gray-300 text-gray-500 dark:bg-slate-800'"
                  class="cursor-pointer my-1 text-left border-l-4 border-gray-300 rounded w-full px-3 py-1.5 shadow bg-white flex justify-between items-center">
                <div>
                  <p class="dark:text-slate-400" :class="weeks.find(x => x.week === i && x.unit.id === unit.id && x.year === year) ? 'dark:text-white' : ''">{{ unit.name }}</p>
                  <p v-if="weeks.find(x => x.week === i && x.unit.id === unit.id && x.year === year)" class="text-sm dark:text-slate-400">
                    {{ weeks.find(x => x.week === i && x.unit.id === unit.id && x.year === year).template_name }}</p>
                  <p v-else class="text-sm dark:text-slate-400">Geen template toegewezen</p>
                </div>

                <div v-if="weeks.find(x => x.week === i && x.unit.id === unit.id && x.year === year)" class="flex justify-between gap-4 items-center">
                  <div v-if="weeks.find(x => x.week === i && x.unit.id === unit.id && x.year === year)?.changed_from_origin">
                    <!--                  Template aangepast van origineel-->
                    <div class="tooltip-wrapper cursor-help">
                      <span class="tooltip-text">Week aangepast van origineel</span>
                      <i class="fa fa-refresh text-red-500 text-xl"></i>
                    </div>
                  </div>
                  <div>
                    <button @click.stop="this.$router.push({name: 'venues.calendar.weeks.edit', params: {venue: this.$route.params.venue, week: weeks.find(x => x.week === i && x.unit.id === unit.id && x.year === year).id}})" class="btn btn-warning">
                      <i class="fa fa-pencil"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </DisclosurePanel>
        </Disclosure>

      </div>
    </div>

    <TransitionRoot appear :show="isOpen" as="template">
      <Dialog as="div" @close="isOpen = false" class="relative z-10">
        <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100"
            leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/70"/>
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto ">
          <div
              class="flex min-h-full items-center justify-center p-4 text-center "
          >
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                  class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-slate-800"
              >
                <DialogTitle
                    as="h3"
                    class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
                >
                  {{ unit.name }}
                </DialogTitle>
                <div class="mt-2">
                  <p class="text-sm text-gray-500 dark:text-slate-400">
                    Pas de template aan voor <span class="font-semibold">{{ unit.name }}</span> in <span
                      class="font-semibold">week {{ week }}</span>
                  </p>
                </div>

                <div>
                  <select v-model="formData.template_id" id="location" name="location" class="input mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option :value="null">Geen template</option>
                    <option v-for="template in templates" :value="template.id">{{ template.name }}</option>
                  </select>
                </div>

                <div class="mt-4 flex justify-end">
                  <button
                      type="button"
                      class="btn btn-primary"
                      @click="postData"
                  >
                    Opslaan
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

  </div>
</template>

<script>
import {DateTime} from 'luxon';
import {
  Dialog,
  DialogDescription,
  DialogPanel,
  DialogTitle,
  Disclosure,
  DisclosureButton,
  DisclosurePanel, Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions, TransitionChild, TransitionRoot
} from "@headlessui/vue";
import {ArrowRightIcon, MinusIcon, PlusIcon} from "@heroicons/vue/16/solid/index.js";

export default {
  name: "Index",
  components: {
    ListboxOptions,
    ListboxOption,
    Listbox,
    ListboxLabel,
    ListboxButton,
    TransitionRoot,
    TransitionChild,
    DisclosurePanel, Disclosure, DisclosureButton, DialogDescription, DialogPanel, DialogTitle, Dialog
  },
  computed: {
    DateTime() {
      return DateTime
    }
  },
  data() {
    return {
      year: new Date().getFullYear(),
      open: false,

      units: [],
      unit: null,
      weeks: [],
      templates: [],

      errors: [],
      isOpen: false,

      formData: {
        template_id: null,
        unit_id: null,
      }
    }
  },

  methods: {
    PlusIcon,
    ArrowRightIcon,
    MinusIcon,

    fetchUnits() {
      axios.get('/venues/' + this.$route.params.venue + '/units/list')
          .then(response => {
            this.units = response.data.data;
          })
    },

    fetchWeeks() {
      axios.get('/venues/' + this.$route.params.venue + '/weeks')
          .then(response => {
            this.weeks = response.data.data;
          })
    },

    fetchTemplates() {
      axios.get('/venues/' + this.$route.params.venue + '/templates/list')
          .then(response => {
            this.templates = response.data.data;
          })
    },

    postData() {
      this.isOpen = false;
      axios.post('/venues/' + this.$route.params.venue + '/weeks/update', {
        year: this.year,
        week: this.week,
        unit_id: this.formData.unit_id,
        template_id: this.formData.template_id
      })
          .then(response => {
            this.errors = [];
            this.week = null;
            this.fetchUnits();
            this.fetchWeeks();
          })
    },

    firstAndLastDayOfWeek(week) {
      let date = DateTime.fromObject({
        weekYear: this.year,
        weekNumber: week
      });
      return date.startOf('week').toFormat('D') + ' - ' + date.endOf('week').toFormat('D');
    },

    openModal(week, unit) {
      this.week = week;
      this.unit = unit;

      this.formData.unit_id = unit.id;
      this.formData.template_id = this.weeks.find(x => x.week === week && x.unit.id === unit.id) ? this.weeks.find(x => x.week === week && x.unit.id === unit.id).template.id : '';

      this.isOpen = true;
    }

  },

  mounted() {
    this.fetchUnits();
    this.fetchWeeks();
    this.fetchTemplates();
  },
}
</script>