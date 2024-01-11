<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import { ref, nextTick, computed } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import RestoreMessage from '@/Components/RestoreMessage.vue';

const props = defineProps({
    user:{
        type:Object,
        required:true
    }
});

const edit = () => {
    router.get(route('users.edit', props.user.id));
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

const deleteUser = () => {
    formDestroy.delete(route('users.destroy',props.user.id), {
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
    <Head title="User Show" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="route('users.index')">Usuarios</Link>
                <span class="text-red-500 font-medium">/</span> {{props.user.name}}
            </h1>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="user.deleted_at != null" class="space-y-4">
                    <RestoreMessage :permission="hasPermission('restaurar usuario')" @restore="router.patch(route('users.restore',props.user.id))">
                        Este usuario esta eliminado, si lo restauras podras acceder y editarlo nuevamente.
                    </RestoreMessage>
                </div>
                <div class="py-9">
                    <CardSection class="max-w-7xl mx-auto">
                        <div class="flex flex-wrap my-12">
                            <div class="mx-auto">
                                <span class="inline text-3xl h-fit">{{ user.name}}</span>
                                <div class="mt-2 text-lg">
                                    <span class="font-semibold">Correo:</span> {{ user.email }}
                                    <br>
                                    <span class="font-semibold">Verificado: </span> {{ user?.email_verified_at  ?? 'No Verificado'}}
                                    <br>
                                    <span class="font-semibold">Creado:</span> {{ user.created_at }}
                                    <br>
                                    <span class="font-semibold">Actualizado:</span> {{ user.updated_at }}
                                    <br>
                                    <span v-if="props.user.deleted_at" class="font-semibold">Eliminado el:</span> {{props.user.deleted_at}}
                                </div>
                                <div class="flex space-x-4 mt-2 p-2">
                                    <div>
                                        <PrimaryButton @click="edit()" v-if="hasPermission('editar usuario') && (user.deleted_at == null)" 
                                        class="w-12/9">
                                            Editar
                                        </PrimaryButton>
                                    </div>
                                    <div>
                                        <DangerButton @click="confirmDestroy()"  v-if="hasPermission('eliminar usuario') && (user.deleted_at == null && user.id != usePage().props.auth.user.id)"
                                        class="w-12/9">
                                            Eliminar
                                        </DangerButton>
                                    </div>
                                </div>
                            </div>
                            <div class="mx-auto">
                                <span class="inline text-3xl h-fit" :class="{'text-red-600 dark:text-red-400' : user.branch  == null}">
                                {{ user.branch?.name ?? 'Sin Centro Asignado'}}</span>
                                <div class="mt-2 text-lg" v-if="user.branch">
                                    <span class="font-semibold">Correo:</span> {{ user.branch?.email }}
                                    <br>
                                    <span class="font-semibold">Telefono:</span> {{ user.branch?.telephone }}
                                    <br>
                                    <span class="font-semibold">Es Central:</span> {{ user.branch?.main ? 'Si':'No' }}
                                    <br>
                                    <span class="font-semibold">Direccion:</span> {{ user.branch?.address }}
                                    <br>
                                    <span class="font-semibold">Zona:</span> {{user.branch?.district.town.state.name}}, {{user.branch?.district.town.name}}, {{ user.branch?.district.name }}
                                </div>
                            </div>
                        </div>
                    </CardSection>
                </div>
              
            </div>    
        </div>
        <div class="py-2">
            <div class="max-w-7xl mx-auto text-center my-4 text-gray-900 dark:text-gray-100" :class="{'text-red-600 dark:text-red-400' : props.user.roles.length == 0}">
                <h2 class="text-3xl font-semibold">{{user.roles.length == 0 ? 'Usuario Sin Rol' : 'Roles'}}</h2>
            </div>

            <div class="flex flex-wrap justify-between items-center gap-4 my-1" v-if="props.user.roles.length != 0">
                <CardSection   v-for="role in props.user.roles" :key="role.id"
                class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow 0 dark:bg-gray-800 dark:border-gray-700 ">     
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"> {{role.name}}</h5>
                    <div class="overflow-y-scroll" style="height: 10rem;">
                        <div v-for="role_permission in role.permissions">
                            <p class="font-normal text-gray-700 dark:text-gray-400">{{role_permission.name}}</p>
                        </div>    
                    </div>
                </CardSection>
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="comfirmingDestroy" @close="closeModalDestroy">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que quieres eliminar este usuario?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez eliminado este usuario, permanecera en la base de datos pero no se podra acceder a el,
                    hasta que se restaure o elimine permanentemente, Esta seguro de que quiere eliminar este auto ?
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Contraseña" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="formDestroy.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        @keydown.enter="deleteUser"
                    />

                    <InputError :message="formDestroy.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalDestroy"> Cancelar </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': formDestroy.processing }"
                        :disabled="formDestroy.processing"
                        @click="deleteUser"
                    >
                        Eliminar Usuario
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>