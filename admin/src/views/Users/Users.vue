<template>
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-3xl font-semibold">Users</h1>
      <button type="submit"
              @click="showUserModal"
              class="flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Add new user
      </button>
    </div>
    <UserModal v-model="showModal" :user="userModel" @close="onModalClose"/>
    <UsersTable @clickEdit="editUser"/>
</template>
   
   <script setup>
   import UsersTable from "./UsersTable.vue"
   import UserModal from "./UserModal.vue"
   import {ref, computed, onMounted} from "vue";
   import store from "../../store";

   const DEFAULT_EMPTY_OBJECT = {
    id:'',
    title:'',
    image:'',
    description:'',
    price:'',
   }
   const showModal = ref(false)
   const userModel = ref({...DEFAULT_EMPTY_OBJECT})
   const users=computed(() => store.state.users);

   function showUserModal(){
       showModal.value = true
   }

   function editUser(user) {
    store.dispatch('getUser', user.id)
      .then(({data}) => {
        userModel.value = data
        showUserModal();
      })
   }

  function onModalClose(){
    userModel.value = {...DEFAULT_EMPTY_OBJECT}
  }
   </script>
   
   <style scoped>
   </style>