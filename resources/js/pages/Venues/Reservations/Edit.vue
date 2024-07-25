<template>
  <div v-if="reservation">
    <div class="mb-12">
      <div class="font-semibold text-lg">Reservering: #{{ reservation.number }}</div>
      <div class="text-sm">Beheer hier de reservering</div>
    </div>

    <div class="mt-6">
      <dl class="grid grid-cols-1 sm:grid-cols-2">
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Naam</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ reservation.name }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Datum</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ $filters.humanDate(reservation.date) }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Email adres</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ reservation.email }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Commentaar/Notities</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ reservation.comments ?? 'Er zijn geen notities' }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Unit(s)</dt>
          <dd v-for="block in blocks" class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ block[0]['unit_name'] }} &mdash; {{ $filters.humanTime(block[0].from) }} - {{ $filters.humanTime(block[block.length - 1].to) }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Acties</dt>
          <dd class="mt-2 text-sm text-gray-900">
            <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
              <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                <div class="flex w-0 flex-1 items-center">
                  <component :is="PaperClipIcon" class="h-5 w-5"></component>
                  <div class="ml-4 flex min-w-0 flex-1 gap-2">
                    <span class="truncate font-medium">Factuur-9C435DE2.pdf</span>
                    <span class="flex-shrink-0 text-gray-400">2.4mb</span>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Factuur opnieuw mailen</a>
                </div>
              </li>
              <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                <div class="flex w-0 flex-1 items-center">
                  <component :is="EnvelopeIcon" class="h-5 w-5"></component>
                  <div class="ml-4 flex min-w-0 flex-1 gap-2">
                    <span class="truncate font-medium">Reserveringsbevestiging</span>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <button @click="resendConfirmationMail" class="font-medium text-indigo-600 hover:text-indigo-500">Bevestiging opnieuw mailen</button>
                </div>
              </li>
            </ul>
          </dd>
        </div>
      </dl>
    </div>
    <div class="flex justify-end gap-4">
      <button @click="$router.go(-1)" class="btn btn-secondary">Terug</button>
    </div>
  </div>
</template>

<script>
import {groupBy} from "lodash";
import {EnvelopeIcon, PaperClipIcon} from "@heroicons/vue/24/outline/index.js";

export default {
  name: "Edit",
  data() {
    return {
      reservation_id: this.$route.params.reservation,
      loading: false,
      reservation: null,
      errors: [],
      blocks: null,

      formData: {
        name: "",
        description: "",
      },
    }
  },

  methods: {
    PaperClipIcon,
    EnvelopeIcon,
    fetchData() {
      if (this.$route.params.reservation != null) {
        this.loading = true;
        axios.get('/venues/' + this.$route.params.venue + '/reservations/' + this.$route.params.reservation)
            .then(response => {
              this.reservation = response.data.data;
              this.group();
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    postData() {
      if(this.loading) return;
      this.loading = true;

      if (this.$route.params.unit != null) {
        axios.put('/venues/' + this.$route.params.venue + '/reservations/' + this.$route.params.reservation, this.formData)
            .then(response => {
              this.$router.push({name: 'venues.reservations.index'});
            }).catch(error => {
          this.errors = error.response.data.errors;
        }).finally(() => {
          this.loading = false;
        })
      }
    },

    resendConfirmationMail() {
      axios.post('/venues/' + this.$route.params.venue + '/reservations/' + this.$route.params.reservation + '/resend/confirmation')
          .then(response => {
            alert('Succesvol verzonden');
          })
    },

    group() {
      this.blocks = groupBy(this.reservation.timeblocks, 'unit_id');
    }
  },

  mounted() {
    this.fetchData();
  },
}
</script>
