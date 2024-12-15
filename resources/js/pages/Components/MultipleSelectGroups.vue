<template>
  <div class="w-72">
    <Listbox v-model="selectedGroups" multiple>
      <div class="relative mt-1 ">
        <ListboxButton
            class="relative dark:bg-slate-800 h-8 w-full cursor-pointer rounded-lg bg-white pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
        >
          <span class="block truncate">{{ selectedGroups.map(g => g.name).join(', ') }}</span>
          <span
              class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
          >
            <ChevronUpDownIcon
                class="h-5 w-5 text-gray-400"
                aria-hidden="true"
            />
          </span>
        </ListboxButton>

        <transition
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
          <ListboxOptions
              class="absolute dark:bg-slate-800 z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
          >
            <ListboxOption
                v-slot="{ active, selected }"
                v-for="group in props.groups"
                :key="group.name"
                :value="group"
                as="template"
            >
              <li
                  :class="[
                  active ? 'bg-indigo-700 text-white' : 'text-gray-900',
                  'group relative cursor-pointer select-none py-2 pl-10 pr-4 dark:text-white',
                ]"
              >
                <span
                    :class="[
                    selected ? 'font-medium' : 'font-normal',
                    'block truncate',
                  ]"
                >{{ group.name }}</span>
                <span
                    v-if="selected"
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-700 group-hover:text-white"
                >
                  <CheckIcon class="h-5 w-5 dark:text-white dark:hover:text-white" aria-hidden="true" />
                </span>
              </li>
            </ListboxOption>
          </ListboxOptions>
        </transition>
      </div>
    </Listbox>
  </div>
</template>

<script setup>
import {ref, defineProps, watch, defineEmits, onMounted} from 'vue'
import {
  Listbox,
  ListboxLabel,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
  groups: {
    type: Array,
    required: true,
  },
  selectedGroupsIds: {
    type: Array,
    required: false,
  }
})

const selectedGroups = ref([])

const emits = defineEmits(['selectionChange']);

onMounted(() => {
  selectedGroups.value = props.groups.filter(u => props.selectedGroupsIds.includes(u.id))
})

watch(selectedGroups, (newValue) => {
  emits('selectionChange', newValue);
})

</script>