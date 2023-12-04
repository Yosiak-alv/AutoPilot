<script setup>
import CardSection from '@/Components/CardSection.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import Paginator from '@/Components/Paginator.vue';

const props = defineProps({
    carId:{
        type:Number,
        required:true
    },
    repairs:{
        type:Object,
        required:true
    },
    filters:{
        type:Object,
        required:true
    },
});

const search = ref(props.filters.search);
//search Handling
watch(search, debounce((value) => {
    router.get(route('cars.show',{id:props.carId}), {search:value}, { preserveState:true , replace:true ,preserveScroll:true});
},500));
</script>

<template>

    <div v-if="props.repairs.data.length != 0 ">
        <div class="max-w-7xl mx-auto text-center my-4 text-gray-900 dark:text-gray-100">
            <h2 class="text-3xl font-semibold">Reparaciones</h2>
            <div class="flex justify-between items-center p-2 mt-4">
                <div>
                    <div class=" relative">
                        <div class="absolute inset-y-0 left-29  flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input type="text" id="search" v-model="search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Buscar">
                    </div>
                </div>
                <div>
                    <Link 
                        :href="route('repairs.create')"
                        method="get" as="button"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                        
                    >
                        + Reparacion
                    </Link>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap justify-between gap-4 my-12">
            <CardSection v-for="repair in props.repairs.data" class="text-base p-2">
                <Link method="get" as="button" :href="route('repairs.show',repair.id)" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{repair.work_shop?.name}},{{repair.car_id}}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">{{repair.status?.name}}</p>
                    <span class="font-semibold">Total:</span> ${{ repair.total }}
                </Link>
            </CardSection>
            
        </div>
        <Paginator :links="props.repairs.links" />
    </div>
    <div v-else>
        <div class="max-w-7xl mx-auto text-center my-4 text-gray-900 dark:text-gray-100">
            <h2 class="text-3xl font-semibold">No hay reparaciones para este Auto.</h2>
            <div class="flex justify-between p-2 mt-4">
                <div>
                    <Link 
                        :href="route('repairs.create')"
                        method="get" as="button"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                        
                    >
                        + Reparacion
                    </Link>
                </div>
            </div>
        </div>
    </div>
    

</template>