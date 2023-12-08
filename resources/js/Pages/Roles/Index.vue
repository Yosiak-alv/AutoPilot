<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import {watch, ref, computed} from "vue";
import CardSection from '@/Components/CardSection.vue';
import Paginator from '@/Components/Paginator.vue';
import TableComponent from '@/Components/TableComponent.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {debounce} from "lodash";

const props = defineProps({
    roles:{
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
});

watch(form,debounce(() => {
    router.get('/roles', {search:form.search}, { preserveState:true , replace:true });
},500));

//create car
const createRole = () => {
    router.get(route('roles.create'), {}, { preserveState:true , replace:true });
}
//permissions
const permissions = ref(usePage().props.auth.user_permissions);
const hasPermission = (permissionName) => {
    return computed(() => permissions.value.includes(permissionName)).value;
};
</script>


<template>

    <Head title="Roles" />   
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Roles</h2>
        </template>

        <div class="py-12">
            <CardSection class="max-w-7xl">
                <div class="flex flex-wrap items-start space-x-2 p-5">
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="search" v-model="form.search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Buscar">
                        </div>
                    </div>
                   <div>
                        <div class="pt-2">
                            <PrimaryButton @click="createRole()" >Agregar Rol</PrimaryButton>
                        </div>
                   </div>   
                </div>

                <div class="p-5">
                    <TableComponent class="rounded-lg bg-gray-100 dark:bg-gray-700">
                        <slot name="heading">
                            <th scope="col" class="px-6 py-3">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Creado el
                            </th>
                            <th scope="col" class="px-6 py-3">
                               Actualizado el
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only"></span>
                            </th>    
                        </slot>
                        <slot>
                            <tr v-for="role in props.roles.data" :key="role.id" class="hover:bg-gray-200 dark:hover:bg-gray-500  bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center">
                                        {{role.id}}
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ role.name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ role.created_at }}
                                </td>
                                <td class="px-6 py-4">
                                    {{role.updated_at}}
                                </td>
                                <td class="px-6 py-4 text-right" >
                                    <Link :href="route('roles.show',{id: role.id})" v-if="hasPermission('ver rol')" >
                                        <svg fill="none" class="block w-6 h-6 " stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                                        </svg>
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="props.roles.data.length === 0">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-gray-200" colspan="4">Roles No Encontrados.</td>
                            </tr>
                        </slot>
                    </TableComponent>

                    <Paginator :links="props.roles.links" />
                </div>
            </CardSection>
        </div>

    </AuthenticatedLayout>

</template>