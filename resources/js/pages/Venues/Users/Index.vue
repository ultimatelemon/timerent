<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Medewerkers</div>
        <div class="text-sm">Beheer hier de medewerkers voor je onderneming</div>
      </div>
      <div>
        <button class="btn btn-primary" @click="newEmployeeModal = true;">Medewerker toevoegen</button>
      </div>
    </div>
<!--    <div class="mb-12">-->
<!--      <div class="font-semibold">Filteren</div>-->
<!--    </div>-->
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Naam</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Gebruikersrol</th>
              <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900">Acties</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-for="user in users" :key="user.id" class="even:bg-gray-50 hover:bg-gray-100 hover:cursor-pointer">
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ user.user.name }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ user.user.email }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">{{ user.owner ? 'Eigenaar' : user.role.name }}</td>
              <td class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900">
                <DeleteButton v-if="current_user.id !== user.id" @delete="deleteData" :id="user.user.id"><i class="fa fa-trash"></i></DeleteButton>
                <span v-else>-</span>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <Modal v-if="newEmployeeModal">
      <div class="p-4 space-y-8">
        <div class="border-b font-semibold pb-2">Een medewerker toevoegen</div>
        <div class="mb-6">
          <label for="email">Email</label>
          <input v-model="formData.email" type="text" id="email" name="email" placeholder="info@timerent.nl" v-on:keyup.enter="addEmployee" />
          <p v-if="errors?.errors?.email" class="text-red-500 pt-3">Dit email adres is nog niet geregistreerd op timerentapp.nl</p>
          <p v-if="errors?.errors?.exists" class="text-red-500 pt-3">{{ errors.errors.exists }}</p>
        </div>
        <div class="mb-6">
          <label for="role_id">Medewerkers rol</label>
          <select class="input" name="role_id" id="role_id" v-model="formData.role_id">
            <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
          </select>
          <p v-if="errors?.errors?.email" class="text-red-500 pt-3">{{ errors.errors.role_id }}</p>
        </div>
        <div class="flex justify-end gap-5">
          <button class="btn btn-secondary" @click="newEmployeeModal = false;">Annuleren</button>
          <button @click="addEmployee" :class="loading ? 'btn btn-secondary opacity-50 cursor-not-allowed' : 'btn btn-primary'"><i v-if="loading" class="fa fa-spinner mr-2 animate-spin"></i> Toevoegen</button>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script>

import Modal from "../../Components/Modals/Modal.vue";
import DeleteButton from "../../Components/DeleteButton.vue";

export default {
  name: "Index",
  components: {DeleteButton, Modal},

  data() {
    return {
      users: [],
      roles: [],
      loading: false,

      formData: {
        email: null,
        role_id: null,
      },

      current_user: null,
      current_user_role: null,

      errors: null,

      pagination: null,
      newEmployeeModal: false,
    }
  },

  methods: {
    fetchUser(venue) {
      axios.get('/users/current?venue=' + venue)
          .then(response => {
            console.log("USER", response.data.data.user);
            this.current_user = response.data.data.user;
            this.current_user_role = response.data.data.role;
          })
    },

    fetchData() {
      axios.get('/venues/' + this.$route.params.venue + '/users')
          .then(response => {
            console.log("U", response.data.data)
            this.users = response.data.data;
            this.pagination = response.data.pagination;
          })
    },

    fetchRoles() {
      axios.get('/venues/' + this.$route.params.venue + '/roles')
          .then(response => {
            this.roles = response.data.data;
          })
    },

    addEmployee() {
      if(this.loading) return;
      this.loading = true;
      this.errors = null;

      axios.post('/venues/' + this.$route.params.venue + '/users', this.formData)
          .then(response => {
            this.email = null;
            this.newEmployeeModal = false;
            this.fetchData();
          })
          .catch(e => {
            this.errors = e.response.data
          })
          .finally(() => {
            this.loading = false;
          })
    },

    deleteData(id) {
      axios.delete('/venues/' + this.$route.params.venue + '/users/' + id)
          .then(() => {
            this.fetchData();
          })
    }
  },

  mounted() {
    this.fetchRoles();
    this.fetchUser(this.$route.params.venue);
    this.fetchData();
  },

}
</script>