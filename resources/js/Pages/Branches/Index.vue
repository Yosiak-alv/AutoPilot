<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link,usePage} from '@inertiajs/vue3';
import {watch, ref, computed} from "vue";
import CardSection from '@/Components/CardSection.vue';
import Paginator from '@/Components/Paginator.vue';
import TableComponent from '@/Components/TableComponent.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {debounce} from "lodash";
const props = defineProps({
    branches:{
        type:Object,
        required:true
    },
    filters:{
        type:Object,
        required:true  
    }
});
const form = useForm({
    search: props.filters.search,
    trashed:props.filters.trashed,
});

watch(form,debounce(() => {
    router.get('/branches', {search:form.search, trashed:form.trashed}, { preserveState:true , replace:true });
},500));

//create branch
const createBranch = () => {
    router.get(route('branches.create'), {}, { preserveState:true , replace:true });
}

//permissions
const permissions = ref(usePage().props.auth.user_permissions);
const hasPermission = (permissionName) => {
    return computed(() => permissions.value.includes(permissionName)).value;
};
</script>

<template>
    <Head title="Branches" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Centros | Fe y Alegria</h2>
        </template>

        <div class="py-12">
            <CardSection class="max-w-7xl">
                <div class="flex flex-wrap justify-between items-center p-5 ">
                    <div>
                        <div class="flex flex-wrap space-x-2 space-y-1">
                            <div class="relative ml-2 mt-1">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="search" v-model="form.search" class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Buscar">
                            </div>
                            <div class="relative">
                                <select v-model="form.trashed" id="trashed" class="p-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500">
                                    <option value="with">Con Eliminados</option>
                                    <option value="only">Solo Eliminados</option>
                                </select>
                            </div>
                            <div>
                                <Link 
                                    :href="route('branches.index')"
                                    method="get" as="button"
                                    class="mt-2 hover:text-red-600 dark:hover:text-red-500 hover:underline"
                                >
                                    Limpiar campos
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="space-x-2 space-y-2">
                        <PrimaryButton class="ml-2" v-if="hasPermission('crear centro')" @click="createBranch()" >Agregar Centro</PrimaryButton>

                        <a :href="route('branches.excelIndexExport',{_query: {
                                                                search: form.search,
                                                                trashed: form.trashed
                                                            },})" 
                            v-if="hasPermission('exportar a excel') && props.branches.data.length != 0"                              
                            class="inline-flex items-center px-4 py-2 bg-green-500  border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-400  focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Excel
                        </a>
                    </div>
                </div>

                <div class="p-5">
                    <TableComponent class="rounded-lg bg-gray-100 dark:bg-gray-700">
                        <slot name="heading">
                            <th scope="col" class="px-6 py-3">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Es Central
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Telefono
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Departamento
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Ciudad
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Distrito
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only"></span>
                            </th>    
                        </slot>
                        <slot>
                            <tr v-for="branch in props.branches.data" :key="branch.id" class="hover:bg-gray-200 dark:hover:bg-gray-500  bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                                    <div class="flex items-center">
                                        {{branch.name}}
                                        <svg fill="none" v-if="branch.deleted_at != null" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                        </svg>
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ branch.main == 1 ? 'Si' : 'No'}}
                                </td>
                                <td class="px-6 py-4">
                                    {{ branch.telephone }}
                                    
                                </td>
                                <td class="px-6 py-4">
                                    {{branch.state.name}}
                                    
                                </td>
                                <td class="px-6 py-4">
                                    {{branch.town.name}}
                                    
                                </td>
                                <td class="px-6 py-4">
                                    {{branch.district.name}}
                                    
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Link  v-if="hasPermission('ver centro')" :href="route('branches.show',{id: branch.id})">
                                        <svg fill="none" class="block w-6 h-6 " stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                                        </svg>
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="props.branches.data.length === 0">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-gray-200" colspan="4">Centro no Encontrado.</td>
                            </tr>
                        </slot>
                    </TableComponent>

                    <Paginator :links="props.branches.links" />
                </div>
            </CardSection>
        </div>

    </AuthenticatedLayout>
</template>