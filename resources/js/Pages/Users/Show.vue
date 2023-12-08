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
import ForceDeleteMessage from '@/Components/ForceDeleteMessage.vue';

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
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Usuario #{{props.user.id}}</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="user.deleted_at != null" class="space-y-4">
                    <RestoreMessage :permission="hasPermission('restaurar usuario')" @restore="router.patch(route('users.restore',props.user.id))">
                        Este usuario esta eliminado, si lo restauras podras acceder y editarlo nuevamente.
                    </RestoreMessage>
                    <ForceDeleteMessage :permission="hasPermission('force-delete usuario')" :url="'users.forceDelete'" :modelToForceDeleteId="props.user.id">
                        Este usuario esta eliminado, si lo eliminas permanentemente no podras acceder a el nuevamente.
                    </ForceDeleteMessage>
                </div>

                <div class="flex flex-wrap justify-between gap-2 my-4">
                    <CardSection>
                        <div class="grid grid-cols-4 gap-4 p-6 items-center">
                            <div class="p-5 col-span-3">
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
                               
                           </div>
                           <div class="flex space-x-4 mt-2 col-span-1">
                                <PrimaryButton @click="edit()" v-if="hasPermission('editar usuario') && (user.deleted_at == null && user.id != usePage().props.auth.user.id)" 
                                class="w-12/9">
                                    Editar
                                </PrimaryButton>
                                <DangerButton @click="confirmDestroy()"  v-if="hasPermission('eliminar usuario') && (user.deleted_at == null && user.id != usePage().props.auth.user.id)"
                                class="w-12/9">
                                    Eliminar
                                </DangerButton>
                            </div>
                        </div>
                   </CardSection>        
                </div>
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