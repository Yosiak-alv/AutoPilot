<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import CardSection from '@/Components/CardSection.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


const props = defineProps({
    user:{
        type:Object,
        required:false
    },
    user_roles:{
        type:Array,
        required:false
    },
    roles:{
        type:Object,
        required:true
    },
    branches:{
        type:Object,
        required:true
    },
});

const form = useForm({
    id: props.user?.id ?? '',
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    branch_id: props.user?.branch_id ?? '',
    roles_id: props.user_roles ?? [],
});

const store = () => {
    form.post(route('users.store'));
};
const update = (id) => {
    form.patch(route('users.update', id));
};
</script>

<template>
<Head title="User Form" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="props.user == null ? route('users.index') : route('users.show',props.user.id)">
                    {{props.user == null ? 'Usuarios' : props.user.name}}
                </Link>
                <span class="text-red-500 font-medium">/</span> {{props.user == null ? 'Crear Nuevo  Usuario': 'Actualiza un Usuario'}}
            </h1>
        </template>

        <div class="py-12">
            <CardSection class="max-w-7xl">
                <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                    <form @submit.prevent="props.user == null ? store() : update(form.id)">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div>
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
                            <div>
                                <InputLabel for="email" value="Correo" />

                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                            <div class="sm:col-span-2">
                                <InputLabel for="branch_id" value="Centro" />
                                <select 
                                    id="branch_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                    v-model="form.branch_id"
                                    required
                                >
                                    <option v-for="branch in props.branches " :value="branch.id" :key="branch.id">
                                        {{branch.name}}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.branch_id" />
                            </div>
                            <div class="sm:col-span-2" v-if="props.roles.length != 0">  
                                <InputLabel for="roles" value="Roles"/>
                                <div id="roles" class="overflow-y-auto" style="height: 8rem;">
                                    <div v-for="role in props.roles" :key="role.id">
                                        <div class="flex flex-col mb-4 ml-4 mt-3">
                                            <div class="flex items-center space-x-3 mt-1">
                                                <input :value="role.id" v-model="form.roles_id" id="roles_id" type="checkbox" 
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <InputLabel for="roles_id" :value="role.name"/> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.roles_id" />
                            </div>
                            <div class="sm:col-span-2" v-else>
                                <div class="text-gray-900 dark:text-gray-100">
                                    <h2 class="text-sm font-semibold">No Hay Roles Registrados, Por Favor Registre un Rol antes de Continuar o Contacte al Administrador.</h2>
                                </div>
                                <InputError class="mt-2" :message="form.errors.roles_id" />
                            </div>
                        </div>
                        <div class="flex justify-start mt-4">
                            <PrimaryButton :disabled="form.processing">{{props.user == null ? 'Crear' : 'Actualizar'}}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </CardSection>
        </div>
    </AuthenticatedLayout>
</template>