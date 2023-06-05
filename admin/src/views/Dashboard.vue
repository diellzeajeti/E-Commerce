<template>
    <h1 class="text-4xl mb-3"> Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">

        <!-- Active Customers -->
      <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
       <label>Active Customers</label>
       <span class="text-3xl font-semibold block mb-2">1500</span>
      </div>
      <!--/ Active Customers -->

       <!-- Active Products -->
       <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
       <label>Active Products</label>
       <span class="text-3xl font-semibold block mb-2">280</span>
      </div>
      <!--/ Active Products -->

       <!-- Paid Orders -->
       <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
       <label>Paid Orders</label>
       <span class="text-3xl font-semibold block mb-2">282036</span>
      </div>
      <!--/ Paid Orders -->

      <!-- Total Income -->
      <div class="bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center ">
       <label>Total Income</label>
       <span class="text-3xl font-semibold block mb-2">$28,026</span>
      </div>
      <!--/ Total Income -->
    </div>
      
    <div class="grid grid-rows-2 grid-flow-col grid-cols-1 md:grid-cols-3 gap-3">
        <div class="col-span-1 row-span-6 bg-white py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
            Products 
        </div>
    <div class=" bg-white  py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
        Customers
    </div>

    </div>
</template>

<script>
export default{
    name: "Dashboard"
}

</script>

<style scoped>

</style>
