<template>

    <TransitionRoot :show="show" as="template">
      <Dialog as="div" @close="show=false" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-75" />
        </TransitionChild>
  
        <div class="fixed z-10 inset-0 overflow-y-auto">
          <div
            class="flex min-h-full items-end sm:items-center justify-center p-4 text-center sm:p-0"
          >
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-[700px] sm:w-full"
              >
              <Spinner v-if="loading"
                        class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"/>
  
                        <header class="py-3 px-4 flex justify-between items-center">
                           <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900">
                            {{ customer.id ? `Update customer: "${props.customer.first_name} ${props.customer.last_name}"` : 'Create new customer' }}
                          </DialogTitle>
                          <button 
                          @click="closeModal()"
                          class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)] "
                          >
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                      <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                           </svg>
                          </button>
                        </header>
                        <form @submit.prevent="onSubmit">
                          <div classs="bg-white px-4 pt-5 pb-4">
                              <CustomInput class="mb-2 mx-2" v-model="customer.first_name" label="First Name"/>
                              <CustomInput class="mb-2 mx-2" v-model="customer.last_name" label="Last Name"/>
                              <CustomInput class="mb-2 mx-2" v-model="customer.email" label="Email"/>
                              <CustomInput class="mb-2 mx-2" v-model="customer.phone" label="Phone"/>
                              <CustomInput type="checkbox" class="mb-2" v-model="customer.status" label="Status"/>
                              
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                  <h2 class="text-2xl font-semibold mt-6 pb-2 border-b border-gray-300 mx-2">Billing Address</h2>

                                  <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mx-2">
                                    <CustomInput  v-model="customer.billingAddress.address1" label="Address 1"/>
                                    <CustomInput  v-model="customer.billingAddress.address2" label="Address 2"/>
                                    <CustomInput  v-model="customer.billingAddress.city" label="City"/>
                                    <CustomInput  v-model="customer.billingAddress.zipcode" label="Zip code"/>


                                    <CustomInput  type="select" :select-options="countries" v-model="customer.billingAddress.country_code" label="Country"/>
                                    <CustomInput  v-model="customer.billingAddress.state" label="State"/>
                                  </div>      
                                </div>
                                
                                <div>
                                  <h2 class="text-2xl font-semibold mt-6 pb-2 border-b border-gray-300 mx-2">Shipping Address</h2>

                                  <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mx-2">
                                    <CustomInput  v-model="customer.shippingAddress.address1" label="Address 1"/>
                                    <CustomInput  v-model="customer.shippingAddress.address2" label="Address 2"/>
                                    <CustomInput  v-model="customer.shippingAddress.city" label="City"/>
                                    <CustomInput  v-model="customer.shippingAddress.zipcode" label="Zip code"/>
                                    <CustomInput  v-model="customer.shippingAddress.country" label="Country"/>
                                    <CustomInput  v-model="customer.shippingAddress.state" label="State"/>
                                  </div>      
                                </div>

                              </div>

                            </div>
                          <footer class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                           class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2  text-base font-medium  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                            bg-indigo-600 hover:bg-indigo-700  text-white">
                           Submit
                            </button>
                            <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                          @click="closeModal" ref="cancelButtonRef">
                          Cancel
                            </button>
                          </footer>
  
                        </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </template>
  
  <script setup>
  import { ref, onUpdated, computed} from 'vue'
  import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
  } from '@headlessui/vue'
  import store from "../../store";
  import CustomInput from "../../components/core/CustomInput.vue";
  import Spinner from "../../components/core/Spinner.vue";
  
  console.log(props.customer);
  const customer = ref({
    billingAddress: {},
    shippingAddress: {}
     })
  
  const loading = ref(false)
  
  const props = defineProps({
      modelValue: Boolean,
      customer: {
        required: true,
        type: Object,
      }
  })
  
  const emit = defineEmits(['update:modelValue', 'close'])
  
  const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
  })


  const countries = computed(()=> store.state.countries.map(c => ({key: c.code, text: c.name})))
  
  onUpdated(() => {
        customer.value = {
          id: props.customer.id,
          first_name: props.customer.first_name,
          last_name: props.customer.last_name,
          email: props.customer.email,
          phone: props.customer.phone,
          status: props.customer.status,
          billingAddress: {
             ...props.customer.billingAddress
          },
          shippingAddress: {
             ...props.customer.shippingAddress
          }
        }
        
  })
  
  function closeModal() {
    show.value = false
    emit('close')
  }
  
  function onSubmit() {
    loading.value = true
    if(customer.value.id){
      customer.value.status = !!customer.value.status
      store.dispatch('updateCustomer', customer.value)
      .then(response => {
        loading.value = false;
        if(response.status === 200){
          //TOD show notification
          store.dispatch ('getCustomer')
          closeModal()
        }
      })
    }else{
      store.dispatch('createCustomer', customer.value)
      .then(response => {
        loading.value = false;
        if(response.status === 201){
          //TODO show notification
          store.dispatch('getCustomers')
          closeModal()
        }
  
      })
    }
  }
  
  </script>
  
     
     <style scoped>
  
  
     </style>