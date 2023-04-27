<template>
    <div v-if="currentUser.id" class="min-h-full bg-gray-200 flex">
            <!-- Sidebar -->
    
           <Sidebar :class="{'-ml-[200px]': !sidebarOpened}"/>
           
            <!-- / Sidebar -->
            <div class="flex-1">
                <Navbar @toggle-sidebar="toggleSidebar"></Navbar>
                <!-- Content -->
                <main class="p-6">
                    <router-view></router-view>
                </main>
                <!-- / Content-->
            </div>
        </div>
        <div v-else>
            
        </div>
    </template>
    
    <script setup>
    import {ref, computed, onMounted, onUnmounted} from 'vue'
    import Sidebar from './Sidebar.vue';
    import Navbar from "./Navbar.vue";
    import store from "../store";
    
        const {title} = defineProps({
            title: String
        });
        const sidebarOpened = ref(true);
        const currentUser = computed(() => store.state.user.data);
       
        

        
        function toggleSidebar() {
         sidebarOpened.value = !sidebarOpened.value
        }

        onMounted(() => {
            handleSidebarOpened();
            window.addEventListener('resize', handleSidebarOpened);
        })

        onUnmounted(() => {
            store.dispatch('getUser')
            updateSidebarState();
            window.removeEventListener('resize', handleSidebarOpened);
        })
        function handleSidebarOpened(){
            sidebarOpened.value = window.outerWidth > 768;
        }
    </script>
    
    <style scoped>
    </style>