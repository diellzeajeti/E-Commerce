<template>
   <div>
   <div class="grid gap-3 grid-cols-2">
     <router-link :to="{name: 'reports.orders'}" 
                class="bg-white py-2 px-3 text-gray-700 rounded-md text-center"
                active-class="text-indigo-600 bg-indigo-50"> Orders Report
            </router-link>
    <router-link :to="{name: 'reports.customers'}"
                class="bg-white py-2 px-3 text-gray-700 rounded-md text-center"
                active-class="text-indigo-600 bg-indigo-50">Customers Report
            </router-link>
   </div>
   <div class="bg-white p-3 rounded-md mt-3 shadow-md">
    <router-view/>
    </div>
   </div>
</template>


<script>
export default {
    name:"OrdersReport.vue"
}

</script>

<style scoped>

</style>