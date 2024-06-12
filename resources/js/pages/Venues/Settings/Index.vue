<template>
  <div>
    <div class="mb-4 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Instellingen</div>
        <div class="text-sm">Beheer hier de instellingen voor je onderneming</div>
      </div>
    </div>

    <div class="flex overflow-x-scroll gap-4 items-center mb-12">
      <div @click="active = 'general'" :class="active === 'general' ? 'bg-indigo-500 text-white' : 'hover:bg-indigo-500 hover:text-white'" class="text-sm py-1 px-4 rounded cursor-pointer">Algemeen</div>
      <div @click="active = 'finance'" :class="active === 'finance' ? 'bg-indigo-500 text-white' : 'hover:bg-indigo-500 hover:text-white'" class="text-sm py-1 px-4 rounded cursor-pointer">Financien</div>
      <div @click="active = 'reservation'" :class="active === 'reservation' ? 'bg-indigo-500 text-white' : 'hover:bg-indigo-500 hover:text-white'" class="text-sm py-1 px-4 rounded cursor-pointer">Reserveringen</div>
    </div>

    <General v-if="active === 'general'"></General>
    <Finance v-if="active === 'finance'"></Finance>
    <Reservation v-if="active === 'reservation'"></Reservation>

  </div>
</template>

<script>

import General from "./General.vue";
import Finance from "./Finance.vue";
import Reservation from "./Reservation.vue";

export default {
  name: "Index",
  components: {Reservation, Finance, General},
  data() {
    return {
      active: 'general'
    }
  },
}
</script>