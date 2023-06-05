<template>
    <h1 class="text-4xl mb-3"> Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">

        <!-- Active Customers -->
      <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
        <label class="text-lg semibold  block mb-2">Active Customers</label>
        <template v-if="!loading.customersCount">
            <span class="text-3xl font-semibold block mb-2">{{ customersCount }}</span>
        </template>
        <Spinner v-else text="" class=""/>
      </div>
      <!--/ Active Customers -->

       <!-- Active Products -->
       <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
       <label class="text-lg semibold  block mb-2">Active Products</label>
       <template v-if="!loading.productsCount">
        <span class="text-3xl font-semibold block mb-2">{{ productsCount }}</span>
        </template>
        <Spinner v-else text="" class=""/>
      </div>
      <!--/ Active Products -->

       <!-- Paid Orders -->
       <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
       <label class="text-lg semibold  block mb-2">Paid Orders</label>
       <template v-if="!loading.paidOrders">
        <span class="text-3xl font-semibold block mb-2">{{ paidOrders }}</span>
        </template>
        <Spinner v-else text="" class=""/>
       
      </div>
      <!--/ Paid Orders -->

      <!-- Total Income -->
      <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center ">
       <label class="text-lg semibold  block mb-2">Total Income</label>
       <template v-if="!loading.totalIncome">
        <span class="text-3xl font-semibold block mb-2">{{ totalIncome }}</span>
        </template>
        <Spinner v-else text="" class=""/>
       
      </div>
      <!--/ Total Income -->
    </div>
      
    <div class="grid grid-rows-3 grid-flow-col grid-cols-1 md:grid-cols-3 gap-14">
        <div class="col-span-2 row-span-6 bg-white py-6 px-5 rounded-lg shadow">
            <label class="text-lg semibold block mb-2">Latest Orders</label>
            <template v-if="!loading.latestOrders">
                <div v-for="o of latestOrders" :key="o.id" class="py-2 px-3 hover:bg-gray-50">
                    
                    <p>
                    <router-link :to="{name: 'app.orders.view', params: {id: o.id}}" class="text-indigo-700 font-semibold"> 
                        Order  #{{ o.id }}  </router-link>
                       contains {{ o.items }} items, {{ o.total_price }} 
                    </p>
                    <p class="flex justify-between"> 
                        <span>
                            {{ o.first_name }} {{ o.last_name }}
                        </span>
                        <span>{{ o.created_at }} </span>
                    </p>
                    
                </div>
            </template>
            <Spinner v-else text="" class="" />
        </div>
    <div class=" bg-white  py-6 px-5 rounded-lg shadow ">
        <label class="text-lg semibold block mb-2">Latest Customers</label>
       <template v-if="!loading.latestCustomers">
        <router-link  to="/" v-for="c of latestCustomers" :key="c.id" class="mb-3 flex">
        <div class="w-12 h-12 bg-gray-200 flex items-center justify-center rounded-full mr-2">
            <UserIcon class="w-5"/>
        </div>
        <div>
            <h3>{{ c.first_name }} {{ c.last_name }}</h3>
            <p>{{ c.email }}</p>
        </div>
        </router-link>
        </template>
        <Spinner v-else text="" class="" />
    </div>

    </div>

</template>

<script setup>
import { UserIcon } from '@heroicons/vue/outline'
import axiosClient from "../axios";
import {ref} from "vue";
import Spinner from "../components/core/Spinner.vue"

const loading = ref( {
    customersCount: true,
    productsCount: true,
    paidOrders: true,
    totalIncome: true,
    latestCustomers: true,
    latestOrders: true
})
const customersCount = ref(0);
const productsCount = ref(0);
const paidOrders = ref(0);
const totalIncome = ref(0);
const latestCustomers = ref([]);
const latestOrders = ref([]);

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

axiosClient.get(`/dashboard/latest-customers`).then(({data:customers}) => {
   latestCustomers.value = customers;
   loading.value.latestCustomers = false;
})
axiosClient.get(`/dashboard/latest-orders`).then(({data:orders}) => {
   latestOrders.value = orders.data;
   loading.value.latestOrders = false;
})
</script>

<style scoped>

</style>
