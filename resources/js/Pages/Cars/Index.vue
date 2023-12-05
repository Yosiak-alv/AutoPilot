<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link} from '@inertiajs/vue3';
import {watch} from "vue";
import CardSection from '@/Components/CardSection.vue';
import Paginator from '@/Components/Paginator.vue';
import TableComponent from '@/Components/TableComponent.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {debounce} from "lodash";
const props = defineProps({
    cars:{
        type:Object,
        required:true
    },
    filters:{
        type:Object,
        required:true  
    }
});
const form = useForm({
    search: props.filters.search
});

watch(form,debounce(() => {
    router.get('/cars', {search:form.search}, { preserveState:true , replace:true });
},500));

//create car
const createCar = () => {
    router.get(route('cars.create'), {}, { preserveState:true , replace:true });
}
</script>

<template>
    <Head title="Cars" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Autos</h2>
        </template>

        <div class="py-12">
            <CardSection class="max-w-7xl">
                <div class="flex space-x-4 p-5">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" v-model="form.search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Buscar">
                    </div>
                    <div class="pt-2">
                        <PrimaryButton @click="createCar()" >Agregar Auto</PrimaryButton>
                    </div>
                </div>

                <div class="p-5">
                    <TableComponent class="rounded-lg bg-gray-100 dark:bg-gray-700">
                        <slot name="heading">
                            <th scope="col" class="px-6 py-3">
                                Placas
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Año
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Marca
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Modelo
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Sucursal
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only"></span>
                            </th>    
                        </slot>
                        <slot>
                            <tr v-for="car in props.cars.data" :key="car.id" class="hover:bg-gray-200 dark:hover:bg-gray-500  bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                                    {{car.plates}}
                                </th>
                                <td class="px-6 py-4">
                                    {{ car.year }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ car.model.brand.name }}
                                    
                                </td>
                                <td class="px-6 py-4">
                                    {{car.model.name}}
                                    
                                </td>
                                <td class="px-6 py-4">
                                    {{car.branch.name}}
                                    
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Link :href="route('cars.show',{id: car.id})">
                                        <svg fill="none" class="block w-6 h-6 " stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                                        </svg>
                                    </Link>
                                </td>
                            </tr>
                        </slot>
                    </TableComponent>

                    <Paginator :links="props.cars.links" />
                </div>
            </CardSection>
        </div>

    </AuthenticatedLayout>

</template>