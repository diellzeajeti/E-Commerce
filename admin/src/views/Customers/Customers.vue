<template>
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-3xl font-semibold">Customers</h1>
     
    </div>
    <CustomerView v-model="showModal" :customer="customerModel" @close="onModalClose"/>
    <CustomersTable @clickEdit="editCustomer"/>
</template>
   
   <script setup>
   import CustomersTable from "./CustomersTable.vue"
   import CustomerView from "./CustomerView.vue"
   import {ref, computed, onMounted} from "vue";
   import store from "../../store";

   const DEFAULT_EMPTY_OBJECT = {
   
   }
   const showModal = ref(false)
   const customerModel = ref({...DEFAULT_EMPTY_OBJECT})
   const customers=computed(() => store.state.customers);

   function showCustomerModal(){
       showModal.value = true
   }

   function editCustomer(c) {
    store.dispatch('getCustomer', c.id)
        .then(({data}) => {
        customerModel.value = data;
        showCustomerModal();
      })
   }  
  

  function onModalClose(){
    customerModel.value = {...DEFAULT_EMPTY_OBJECT}
  }
   </script>
   
   <style scoped>
   </style>