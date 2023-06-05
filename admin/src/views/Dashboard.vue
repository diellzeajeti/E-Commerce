<template>
    <h1 class="text-4xl mb-3"> Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">

        <!-- Active Customers -->
      <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
        <label>Active Customers</label>
        <template v-if="!loading.customersCount">
            <span class="text-3xl font-semibold block mb-2">{{ customersCount }}</span>
        </template>
        <Spinner v-else text="" class=""/>
      </div>
      <!--/ Active Customers -->

       <!-- Active Products -->
       <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
       <label>Active Products</label>
       <template v-if="!loading.productsCount">
        <span class="text-3xl font-semibold block mb-2">{{ productsCount }}</span>
        </template>
        <Spinner v-else text="" class=""/>
      </div>
      <!--/ Active Products -->

       <!-- Paid Orders -->
       <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
       <label>Paid Orders</label>
       <template v-if="!loading.paidOrders">
        <span class="text-3xl font-semibold block mb-2">{{ paidOrders }}</span>
        </template>
        <Spinner v-else text="" class=""/>
       
      </div>
      <!--/ Paid Orders -->

      <!-- Total Income -->
      <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center ">
       <label>Total Income</label>
       <template v-if="!loading.totalIncome">
        <span class="text-3xl font-semibold block mb-2">{{ totalIncome }}</span>
        </template>
        <Spinner v-else text="" class=""/>
       
      </div>
      <!--/ Total Income -->
    </div>
      
    <div class="grid grid-rows-3 grid-flow-col grid-cols-1 md:grid-cols-3 gap-14">
        <div class="col-span-2 row-span-6 bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
            Products 
        </div>
    <div class=" col-span-2 row-span-6 bg-white  py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
        Customers
    </div>

    </div>

</template>

<script setup>
import axiosClient from "../axios";
import {ref} from "vue";
import Spinner from "../components/core/Spinner.vue"

const loading = ref( {
    customersCount: true,
    productsCount: true,
    paidOrders: true,
    totalIncome: true,
})
const customersCount = ref(0);
const productsCount = ref(0);
const paidOrders = ref(0);
const totalIncome = ref(0);

axiosClient.get(`/dashboard/customers-count`).then(({data}) => {
    customersCount.value = data
    loading.value.customersCount = false;
})
axiosClient.get(`/dashboard/products-count`).then(({data}) => {
    productsCount.value = data
    loading.value.productsCount = false;
})
axiosClient.get(`/dashboard/orders-count`).then(({data}) => {
    paidOrders.value = data
    loading.value.paidOrders = false;
})
axiosClient.get(`/dashboard/income-amount`).then(({data}) => {
    totalIncome.value = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' })
    .format(data);
    loading.value.totalIncome = false;
})

</script>

<style scoped>

</style>
