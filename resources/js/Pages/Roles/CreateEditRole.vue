<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,Link, router,useForm} from '@inertiajs/vue3';
import CardSection from '@/Components/CardSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    role:{
        type:Object,
        required:false
    },
    role_permissions:{
        type:Object,
        required:false
    },
    permissions:{
        type:Object,
        required:true
    },

});

const form = useForm({
    id: props.role?.id ?? '',
    name:props.role?.name ?? '',
    permissions_id: props.role_permissions ?? [],

});

const store = () => {
    form.post(route('roles.store'));
};

const update = () => {
    form.patch(route('roles.update',{id: form.id}));
};
</script>

<template>
    <Head title="Roles" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="props.role  == null ? route('roles.index') : route('roles.show',props.role.id)">
                    {{props.role == null ? 'Roles' : props.role.name}}
                </Link>
                <span class="text-red-500 font-medium">/</span> {{ props.role == null ? 'Crear un Nuevo Rol': 'Editar Rol'}}
            </h1>
        </template>

        <div class="py-12">
            <CardSection class="max-w-7xl mt-6 space-y-6">
                <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                    <form @submit.prevent="(props.role == null ? store(): update())">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-2">
                                <InputLabel for="name" value="Nombre" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                           
                            <div class="sm:col-span-2">
                                <div class="border-b border-gray-400 flex justify-between margin-b mb-4">
                                    <h2 class="text-2xl font-bold mt-3 ">
                                        Permisos
                                    </h2>
                                </div>
                                <div class="overflow-y-auto " style="height: 20rem;">
                                    <div v-for="permission in props.permissions" :key="permission.id">
                                        <div class="flex flex-col mb-4 ml-4">
                                            <div class="flex items-center space-x-3 mt-1">
                                                <input :value="permission.id" v-model="form.permissions_id" id="permissions_id" type="checkbox" 
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <InputLabel for="permissions_id" :value="permission.name"/> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.permissions_id" />
                            </div>
                           
                        </div>
                        <div class="flex justify-start mt-4">
                            <PrimaryButton :disabled="form.processing">{{props.role == null ? 'Crear' : 'Actualizar'}}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </CardSection>
        </div>
    </AuthenticatedLayout>

</template>