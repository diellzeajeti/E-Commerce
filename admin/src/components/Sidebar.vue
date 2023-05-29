<template>
    <div class="w-[200px] transition-all bg-indigo-700 text-white py-4 px-2">
        <router-link :to="{name: 'app.dashboard'}" class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
            <span class="mr-2 text-gray-400">
                <HomeIcon class="w-5"/>
            </span>
            <span class="text-sm">
                Dashboard
            </span>
        </router-link>
        <router-link :to="{name: 'app.products'}" class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
            <span class="mr-2 text-gray-400">
                <ViewListIcon class="w-5"/>
            </span>
            <span class="text-sm">
                Products
            </span>
        </router-link>
        <router-link :to="{name: 'app.orders'}" class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
            <span class="mr-2 text-gray-400">
                <ViewListIcon class="w-5"/>
            </span>
            <span class="text-sm">
                Orders
            </span>
        </router-link>
        <router-link :to="{name: 'app.users'}" class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
            <span class="mr-2 text-gray-400">
                <UsersIcon class="w-5"/>
            </span>
            <span class="text-sm">
                Users
            </span>
        </router-link>
        <router-link :to="{name: 'app.customers'}" class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
            <span class="mr-2 text-gray-400">
                <UserGroupIcon class="w-5"/>
            </span>
            <span class="text-sm">
                Customers
            </span>
        </router-link>
        <router-link :to="{name: 'app.dashboard'}" class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
            <span class="mr-2 text-gray-400">
                <ChartBarIcon class="w-5"/>
            </span>
            <span class="text-sm">
                Reports
            </span>
        </router-link>
        
      </div>
</template>

<script setup>
import { HomeIcon,UserGroupIcon, UsersIcon, ViewListIcon, ChartBarIcon } from '@heroicons/vue/outline'
</script>

<style scoped>

</style>