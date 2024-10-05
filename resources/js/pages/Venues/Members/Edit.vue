<template>
  <div v-if="member">
    <div class="mb-12">
      <div class="font-semibold text-lg">Member: #{{ member.id.split('-')[0].toUpperCase() }}</div>
      <div class="text-sm">Beheer de huidige gebruiker</div>
    </div>

    <div class="flex gap-6 text-sm border-b border-gray-200 pb-8">
      <div>
        <router-link :to="{name: 'venues.members.edit', params: {venue: this.$route.params.venue, member: member.id}}" active-class="border-indigo-500" class="border-b-2 hover:border-indigo-500 pb-1">Gebruiker</router-link>
      </div>
      <div>
        <router-link :to="{name: 'venues.members.reservations', params: {venue: this.$route.params.venue, member: member.id}}" active-class="border-indigo-500" class="border-b-2 hover:border-indigo-500 pb-1">Reserveringen</router-link>
      </div>
    </div>

    <div class="mt-6">
      <dl class="grid grid-cols-1 sm:grid-cols-2">
        <div class=" px-4 py-6 sm:col-span-1 sm:px-0 mr-0 sm:mr-12">
          <dt class="text-sm font-semibold leading-6 text-gray-900 pb-2">Naam</dt>
          <input v-on:keyup.enter="postData" class="text-sm" v-model="formData.name">
          <span v-if="errors.name" class="text-sm text-red-500">{{ errors.name[0] }}</span>
        </div>
        <div class="border-t border-gray-100 md:border-none px-4 py-6 sm:col-span-1 sm:px-0 mr-0 sm:mr-12">
          <dt class="text-sm font-semibold leading-6 text-gray-900 pb-2">Email</dt>
          <input v-on:keyup.enter="postData" class="text-sm" v-model="formData.email">
          <span v-if="errors.email" class="text-sm text-red-500">{{ errors.email[0] }}</span>
        </div>
        <div class="border-t border-gray-100 md:border-none px-4 py-6 sm:col-span-1 sm:px-0 mr-0 sm:mr-12">
          <dt class="text-sm font-semibold leading-6 text-gray-900 pb-2">Groep</dt>
          <select class="input" name="" id="" v-model="formData.group_id">
            <option :value="null">Geen groep</option>
            <option :value="group.id" v-for="group in groups">{{group.name}}</option>
          </select>
          <span v-if="errors.group_id" class="text-sm text-red-500">{{ errors.group_id[0] }}</span>
        </div>
        <div class="invisible border-t border-gray-100 md:border-none px-4 py-6 sm:col-span-1 sm:px-0 mr-0 sm:mr-12">
<!--          <dt class="text-sm font-semibold leading-6 text-gray-900 pb-2">Email</dt>-->
<!--          <input v-on:keyup.enter="postData" class="text-sm" v-model="formData.email">-->
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0 mr-0 sm:mr-12">
          <dt class="text-sm font-semibold leading-6 text-gray-900 pb-2">Loyality Points <span class="font-normal text-xs">* Not implemented yet</span></dt>
          <input v-on:keyup.enter="postData" type="number" class="text-sm" v-model="formData.loyality_points">
          <span v-if="errors.loyality_points" class="text-sm text-red-500">{{ errors.loyality_points[0] }}</span>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0 mr-0 sm:mr-12">
          <dt class="text-sm font-semibold leading-6 text-gray-900 pb-2">Notities (alleen zichtbaar voor je medewerkers)</dt>
          <textarea class="text-sm input p-2" v-model="formData.notes"></textarea>
          <span v-if="errors.notes" class="text-sm text-red-500">{{ errors.notes[0] }}</span>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Registratie datum</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:mt-2">{{ $filters.humanDate(member.created_at) }}</dd>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-1 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Betalen op factuur <span class="font-normal text-xs">* Not implemented yet</span></dt>
          <div class="relative mt-2">
            <Switch v-model="formData.pay_on_invoice" :class="[formData.pay_on_invoice ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">
              <span class="sr-only">Use setting</span>
              <span :class="[formData.pay_on_invoice ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']">
            <span :class="[formData.pay_on_invoice ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">
              <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
               <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
             </span>
            <span :class="[formData.pay_on_invoice ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']" aria-hidden="true">
             <svg class="h-3 w-3 text-indigo-600" fill="currentColor" viewBox="0 0 12 12">
               <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
             </svg>
            </span>
            </span>
            </Switch>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
              <svg v-if="errors.pay_on_invoice" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                      clip-rule="evenodd"/>
              </svg>
            </div>
          </div>
          <span v-if="errors.pay_on_invoice" class="text-sm text-red-500">{{ errors.pay_on_invoice[0] }}</span>
        </div>
        <div class="border-t border-gray-100 px-4 py-6 sm:col-span-2 sm:px-0">
          <dt class="text-sm font-semibold leading-6 text-gray-900">Acties</dt>
          <dd class="mt-2 text-sm text-gray-900">
            <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
<!--              <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">-->
<!--                <div class="flex w-0 flex-1 items-center">-->
<!--                  <component :is="PaperClipIcon" class="h-5 w-5"></component>-->
<!--                  <div class="ml-4 flex min-w-0 flex-1 gap-2">-->
<!--                    <span class="truncate font-medium">Factuur-9C435DE2.pdf</span>-->
<!--                    <span class="flex-shrink-0 text-gray-400">2.4mb</span>-->
<!--                  </div>-->
<!--                </div>-->
<!--                <div class="ml-4 flex-shrink-0">-->
<!--                  <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Factuur opnieuw mailen</a>-->
<!--                </div>-->
<!--              </li>-->
              <li v-if="!member.email_verified_at" class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                <div class="flex w-0 flex-1 items-center">
                  <component :is="EnvelopeIcon" class="h-5 w-5"></component>
                  <div class="ml-4 flex min-w-0 flex-1 gap-2">
                    <span class="truncate font-medium">Email niet geverifieerd</span>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0 gap-4 flex">
                  <button @click="resendConfirmationMail" class="font-medium text-indigo-600 hover:text-indigo-500">Verificatie mail opnieuw sturen</button>
                  <span class="text-black font-thin">&#8212;</span>
                  <button @click="resendConfirmationMail" class="font-medium text-indigo-600 hover:text-indigo-500">Handmatig verifieren</button>
                </div>
              </li>
            </ul>
          </dd>
        </div>
      </dl>
    </div>
    <div class="flex justify-end gap-4">
      <button @click="$router.go(-1)" class="btn btn-secondary">Terug</button>
      <button @click="postData" class="btn btn-primary"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Opslaan</button>
    </div>
  </div>
</template>

<script>
import {groupBy} from "lodash";
import {EnvelopeIcon, PaperClipIcon} from "@heroicons/vue/24/outline/index.js";
import {Switch} from "@headlessui/vue";

export default {
  name: "Edit",
  components: {Switch},
  data() {
    return {
      member_id: this.$route.params.member,
      loading: true,
      member: null,
      errors: [],
      groups: [],
      blocks: null,

      formData: {
        name: "",
        email: "",
        notes: null,
        loyality_points: 0,
        pay_on_invoice: false,
        group_id: null,
      },
    }
  },

  methods: {
    PaperClipIcon,
    EnvelopeIcon,
    fetchData() {
      if (this.$route.params.member != null) {
        this.loading = true;
        axios.get('/venues/' + this.$route.params.venue + '/members/' + this.$route.params.member)
            .then(response => {
              this.member = response.data.data;
              this.formData.name = response.data.data.name;
              this.formData.email = response.data.data.email;
              this.formData.notes = response.data.data.notes;
              this.formData.loyality_points = response.data.data.loyality_points;
              this.formData.pay_on_invoice = response.data.data.pay_on_invoice;
              this.formData.group_id = response.data.data.group.id;
            })
            .finally(() => {
              this.loading = false;
            })
      }
    },

    postData() {
      if(this.loading) return;
      this.loading = true;

      if (this.$route.params.member != null) {
        axios.put('/venues/' + this.$route.params.venue + '/members/' + this.$route.params.member, this.formData)
            .then(response => {
              this.$router.push({name: 'venues.members.index', params: {venue: this.$route.params.venue}});
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

    fetchGroups() {
      axios.get('/venues/' + this.$route.params.venue + '/groups')
          .then(response => {
            this.groups = response.data.data;
          })
    },

    group() {
      this.blocks = groupBy(this.reservation.timeblocks, 'unit_id');
    }
  },

  mounted() {
    this.fetchData();
    this.fetchGroups();
  },
}
</script>
