<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import { ref,computed, nextTick } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    role:{
        type:Object,
        required:true
    },
});

const edit = () => {
    router.get(route('roles.edit', props.role.id));
}
//Destroy Modal
const comfirmingDestroy = ref(false);
const passwordInput = ref(null);

const formDestroy = useForm({
    password: '',
});
const confirmDestroy = () => {
    comfirmingDestroy.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteRole = () => {
    formDestroy.delete(route('roles.destroy',props.role.id), {
        preserveScroll: true,
        onSuccess: () => closeModalDestroy(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => formDestroy.reset(),
    });
};

const closeModalDestroy = () => {
    comfirmingDestroy.value = false;
    formDestroy.reset();
    formDestroy.clearErrors();
};
//permissions
const permissions = ref(usePage().props.auth.user_permissions);
const hasPermission = (permissionName) => {
    return computed(() => permissions.value.includes(permissionName)).value;
};
</script>

<template>
<Head title="Role Show" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="route('roles.index')">Roles</Link>
                <span class="text-red-500 font-medium">/</span> {{props.role.name}}
            </h1>
        </template>
        
        <div class="py-9">
            <CardSection class="max-w-7xl mx-auto ">
                <div class="flex flex-wrap my-12">
                    <div class="mx-auto">
                        <span class="inline text-3xl h-fit">{{ props.role.name }}</span>
                        <div class="mt-2 text-lg mb-2">
                            <span class="font-semibold">Creado:</span> {{ props.role.created_at }}
                            <br>
                            <span class="font-semibold">Actualizado:</span> {{ props.role.updated_at }}
                        </div>
                    </div>
                    <div class="mx-auto items-center text-center">
                        <div>
                            <PrimaryButton v-if="hasPermission('editar rol') && props.role.name != 'Super-Admin'" class="ml-8" @click="edit()">
                                Editar
                            </PrimaryButton>
                        </div>
                        <div>
                            <DangerButton  v-if="hasPermission('eliminar rol') && props.role.name != 'Super-Admin'" class="mt-2 ml-8" @click="confirmDestroy()" >
                                Eliminar 
                            </DangerButton>
                        </div>
                    </div>
                </div>
            </CardSection>
        </div>  

        <div class="py-2">
            <div class="max-w-7xl mx-auto text-center my-4 text-gray-900 dark:text-gray-100">
                <h2 class="text-3xl font-semibold">Permisos</h2>
            </div>

            <div class="mt-4">
                <div class="flex flex-wrap justify-between gap-4 my-1">
                    <div class="flex justify-center mx-auto" >
                        <div class="flex flex-col mb-4">
                            <CardSection  class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow 0 dark:bg-gray-800 dark:border-gray-700 ">
                                <div class="overflow-y-scroll" style="height: 10rem;">
                                    <div v-for="permission in props.role.permissions">
                                        <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white"> {{permission.name}}</h5>
                                    </div>
                                </div>
                            </CardSection>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <Modal :show="comfirmingDestroy" @close="closeModalDestroy">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que quieres eliminar este Rol?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez eliminado este registro, no podras recuperarlo, se eliminaran permanentemente el registro y todas sus relaciones a el quedaran vacias. 
                    ¿Estas seguro que quieres eliminarlo permanentemente?
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Contraseña" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="formDestroy.password"
                        type="password"
                        class="mt-1 block w-3/4"
                    />

                    <InputError :message="formDestroy.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalDestroy"> Cancelar </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': formDestroy.processing }"
                        :disabled="formDestroy.processing"
                        @click="deleteRole"
                    >
                        Eliminar 
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>