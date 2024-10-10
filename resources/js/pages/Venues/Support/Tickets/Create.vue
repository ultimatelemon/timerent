<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Nieuw ticket</div>
        <div class="text-sm">Maak een nieuw ticket aan en ons support team neemt zo snel mogelijk contact met je op.
        </div>
      </div>
      <div>
        <!--        -->
      </div>
    </div>

    <div class="w-full xl:w-1/2">
      <div v-if="success" class="alert alert-success mb-12">
        <div>Je ticket is aangemaakt!</div>
        <div @click="success = false;" class="cursor-pointer"><i class="fa fa-close"></i></div>
      </div>
      <div>
        <div class="font-semibold text-md mb-2">Ticket informatie</div>
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-12 mb-12">
          <div>
            <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Titel <span
                class="required-star">*</span></label>
            <input type="text"
                   name="title"
                   id="title"
                   v-model="formData.title"
                   placeholder="Waar gaat je vraag over?"
            />
            <span v-if="errors.title" class="text-red-500 text-sm">{{ errors.title[0] }}</span>
          </div>
          <div>
            <label for="ticket_type" class="block text-sm font-medium leading-6 text-gray-900">Type <span
                class="required-star">*</span></label>
            <select class="input" name="ticket_type" id="ticket_type" v-model="formData.type">
              <option :value="null" :disabled="true">Selecteer een ticket type</option>
              <option value="software_issue">Software issue</option>
              <option value="question">Question</option>
              <option value="feedback">Feedback</option>
              <option value="critical">Critical</option>
            </select>
            <span v-if="errors.type" class="text-red-500 text-sm">{{ errors.type[0] }}</span>
          </div>
          <div>
            <label for="message" class="block text-sm font-medium leading-6 text-gray-900">Je bericht <span
                class="required-star">*</span></label>
            <textarea type="text"
                      name="message"
                      id="message"
                      v-model="formData.message"
                      rows="8"
                      class="input"
                      placeholder="Beschrijf hier zo duidelijk mogelijk je vraag zodat we je zo goed en snel mogelijk kunnen helpen :)"
            ></textarea>
            <div class="text-xs flex justify-end mt-2" :class="formData.message.split('').length > 511 ? 'text-red-500' : ''">{{ formData.message.split('').length }}/<span>511</span></div>
            <span v-if="errors.message" class="text-red-500 text-sm">{{ errors.message[0] }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="flex justify-end gap-4">
      <button @click="$router.go(-1)" class="btn btn-secondary">Annuleren</button>
      <button @click="postData" :class="loading ? 'btn-secondary' : 'btn-primary'" class="btn"><i v-if="loading" class="fa fa-spinner fa-spin mr-2"></i> Opslaan</button>
    </div>

  </div>
</template>

<script>
import DeleteModal from "../../../Components/Modals/DeleteModal.vue";

export default {
  name: "Create",
  components: {DeleteModal},
  data() {
    return {
      loading: false,
      success: false,
      errors: [],

      formData: {
        title: null,
        type: null,
        message: null,
      },
    }
  },

  methods: {
    postData() {
      if(this.loading) return;
      this.loading = true;

      axios.post('/venues/' + this.$route.params.venue + '/tickets', this.formData)
          .then(response => {
            console.log(response.data.data);
            this.success = true;
            this.errors = [];
            this.$router.push({name: 'venues.support.tickets.index'});
          })
          .catch(e => {
            this.errors = e.response.data.errors;
            this.success = false;
          })
          .finally(() => {
            this.loading = false;
          })
    }
  },

  computed: {
    current_venue: {
      get() {
        return this.$store.state.venue;
      }
    },

    current_user: {
      get() {
        return this.$store.state.user;
      }
    }
  }
}
</script>
