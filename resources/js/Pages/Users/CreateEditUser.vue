<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import CardSection from '@/Components/CardSection.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaComponent from '@/Components/TextAreaComponent.vue';
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
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{props.user == null ? 'Crear Nuevo  Usuario': 'Actualiza un Usuario'}}</h2>
        </template>

        <div class="py-12">
        <CardSection class="max-w-7xl">
           <form @submit.prevent="props.user == null ? store() : update(form.id)" class="space-y-6">
                <div class="grid grid-cols-2 gap-4 p-6">
                    <div>
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
                        <div class="mt-2">
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
                    </div>
                    <div>                       
                        <div class="mt-2">
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
                        <div>
                            <InputLabel for="roles_id" value="Roles" class="mt-2" />
                            <select multiple
                                id="roles_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                                v-model="form.roles_id"
                                required
                            >
                                <option v-for="role in props.roles" :value="role.id" :key="role.id">
                                    {{role.name}}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.roles_id" />
                        </div>
                    </div>
                    <div class="flex items-center gap-4 mt-3">
                        <PrimaryButton :disabled="form.processing">{{props.user == null ? 'Crear' : 'Actualizar'}}</PrimaryButton>
                    </div>
                </div>
           </form>
        </CardSection>
        </div>
    </AuthenticatedLayout>
</template>