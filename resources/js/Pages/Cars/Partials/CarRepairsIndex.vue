<script setup>
import CardSection from '@/Components/CardSection.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed, reactive } from 'vue';
import { debounce } from 'lodash';
import Paginator from '@/Components/Paginator.vue';
import InputLabel from '@/Components/InputLabel.vue';
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
    deleted:{
        type:Boolean,
        required:false,
        default:false
    }
});

const form  = reactive({
    search:props.filters.search,
    start_date:props.filters.start_date,
    end_date:props.filters.end_date,
});
//search Handling
watch(form, debounce(() => {
    router.get(route('cars.show',{id:props.carId}), {search:form.search, start_date:form.start_date, end_date:form.end_date}, 
    { preserveState:true , replace:true ,preserveScroll:true});
},500));

//permissions
const permissions = ref(usePage().props.auth.user_permissions);
const hasPermission = (permissionName) => {
    return computed(() => permissions.value.includes(permissionName)).value;
};
</script>

<template>

    <div class="max-w-7xl mx-auto text-center my-4 text-gray-900 dark:text-gray-100">
        <h2 class="text-3xl font-semibold">Reparaciones</h2>
        <div class="flex flex-wrap justify-between items-center p-2 mt-4">
            <div class="mx-auto mt-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-29  flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input type="text" id="search" v-model="form.search" class="mt-4    bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Buscar">
                </div>
            </div>
            <div class="mx-auto mt-2">
                <div class="relative max-w-sm">
                    <div class="absolute inset-y-0 end-0 flex items-center ps-3.5 mr-3 mt-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </div>
                    <InputLabel for="start_date" value="Fecha Inicio: " />
                    <input id="start_date" type="date" v-model="form.start_date" class="bg-gray-50 border border-gray-300 text-gray-900 dark:text-white text-sm rounded-lg focus:border-red-500 dark:focus:border-red-600 focus:ring-red-500 dark:focus:ring-red-600 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" >
                </div>
            </div>
            <div class="mx-auto mt-2">
                <div class="relative max-w-sm">
                    <div class="absolute inset-y-0 end-0 flex items-center ps-3.5 mr-3 mt-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </div>
                    <InputLabel for="end_date" value="Fecha Fin: " />
                    <input id="end_date" type="date" v-model="form.end_date" class="bg-gray-50 border border-gray-300 text-gray-900 dark:text-white text-sm rounded-lg focus:border-red-500 dark:focus:border-red-600 focus:ring-red-500 dark:focus:ring-red-600 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" >
                </div>
            </div>
            <div class="space-x-2 space-y-2 mx-auto mt-4">
                <Link 
                    :href="route('cars.show',props.carId)"
                    method="get" as="button"
                    class="hover:text-red-500 dark:hover:text-red-600 hover:underline"
                    preserve-scroll
                >
                    Limpiar campos
                </Link>
                <a :href="route('cars.excelRepairsExport',{car:props.carId , _query: {
                                                                search:form.search, 
                                                                start_date:form.start_date,
                                                                end_date:form.end_date
                                                            },})" v-if="hasPermission('exportar a excel') && props.repairs.data.length != 0"                       
                class="mt-2 inline-flex items-center px-4 py-2 bg-green-500  border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-400  focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Excel
                </a>
                <Link 
                    :href="route('repairs.create')"
                    method="get" as="button"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                    v-if="hasPermission('crear reparacion') && !props.deleted"
                >
                    + Reparacion
                </Link>
            </div>
        </div>
    </div>
    <div v-if="props.repairs.data.length != 0 ">
        

        <div class="flex flex-wrap justify-between gap-4 my-12">
            <CardSection v-for="repair in props.repairs.data" class="text-base p-2">
                <Link  v-if="hasPermission('ver reparacion')"
                    :preserve-scroll="false"
                    method="get" as="button" :href="route('repairs.show',repair.id)" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{repair.work_shop?.name ?? 'Taller Eliminado'}}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">{{repair.status?.name}}</p>
                    <span class="font-semibold">Total:</span> ${{ repair.total }} <br>
                    <span class="font-semibold">Fecha:</span> {{ repair.repair_date }}
                </Link>
                <div v-else
                    class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700"
                >
                    <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">{{repair.work_shop?.name ?? 'Taller Eliminado'}}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">{{repair.status?.name}}</p>
                    <span class="font-semibold">Total:</span> ${{ repair.total }} <br>
                    <span class="font-semibold">Fecha:</span> {{ repair.repair_date }}
                </div>
            </CardSection>
            
        </div>
        <Paginator :links="props.repairs.links" />
    </div>
    <div v-else>
        <div class="max-w-7xl mx-auto text-center my-4 text-gray-900 dark:text-gray-100">
            <h2 class="text-3xl font-semibold">No hay reparaciones para este Auto.</h2>
        </div>
    </div>
</template>