<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Orders</h1>      
    </div>
    <OrdersTable @clickView="viewOrder"/>
</template>


<script setup>
 import OrdersTable from "./OrdersTable.vue";
   
   import {ref, computed, onMounted} from "vue";
   import store from "../../store";

   

   const showModal = ref(false)
   
   const orders=computed(() => store.state.orders);

   

   function showOrder(product) {
   
   }

  
</script>


<style scoped>
</style>