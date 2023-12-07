<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import {watch,ref,computed} from "vue";
import CardSection from '@/Components/CardSection.vue';
import Paginator from '@/Components/Paginator.vue';
import TableComponent from '@/Components/TableComponent.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

import {debounce} from "lodash";
const props = defineProps({
    brands:{
        type:Object,
        required:true
    },
    filters:{
        type:Object,
        required:true  
    }
});
const search = ref(props.filters.search);

watch(search,debounce((value) => {
    router.get('/brands', {search:value}, { preserveState:true , replace:true });
},500));

const form = useForm({
    name: '',
}); 

const confirmingBrandCreation = ref(false);
const confirmBrandCreation = () => confirmingBrandCreation.value = true;
const closeModal = () => {
    confirmingBrandCreation.value = false;
    form.reset();
    form.clearErrors();
};
// Modal create brand
const createBrand = () => {
    form.post(route('brands.store'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => console.log('error'),
        onFinish: () => (form.reset()),
    });
}
//permissions
const permissions = ref(usePage().props.auth.user_permissions);
const hasPermission = (permissionName) => {
    return computed(() => permissions.value.includes(permissionName)).value;
};
</script>

<template>
    <Head title="Brands" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Marcas</h2>
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
                        <input type="search" v-model="search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" placeholder="Buscar">
                    </div>
                    <div class="pt-2">
                        <PrimaryButton v-if="hasPermission('crear marca')" @click="confirmBrandCreation()">Agregar Marca</PrimaryButton>
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
                            <tr v-for="brand in props.brands.data" :key="brand.id" class="hover:bg-gray-200 dark:hover:bg-gray-500  bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                                    {{brand.id}}
                                </th>
                                <td class="px-6 py-4">
                                    {{ brand.name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ brand.created_at}}
                                    
                                </td>
                                <td class="px-6 py-4">
                                    {{brand.updated_at}}
                                    
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Link :href="route('brands.show',{id: brand.id})" v-if="hasPermission('ver marca')">
                                        <svg fill="none" class="block w-6 h-6 " stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                                        </svg>
                                    </Link>
                                </td>
                            </tr>
                        </slot>
                    </TableComponent>

                    <Paginator :links="props.brands.links" />
                </div>
            </CardSection>
        </div>

    </AuthenticatedLayout>

    <Modal :show="confirmingBrandCreation" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Crear Nueva Marca
            </h2>

            <div class="mt-6">
                <InputLabel for="name" value="Nombre" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="createBrand()"
                >
                    Guardar
                </PrimaryButton>
            </div>
        </div>
    </Modal>

</template>