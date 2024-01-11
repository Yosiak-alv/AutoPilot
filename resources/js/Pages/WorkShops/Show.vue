<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link,usePage} from '@inertiajs/vue3';
import { ref, computed, nextTick } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import RestoreMessage from '@/Components/RestoreMessage.vue';
import WorkShopRepairIndex from './Partials/WorkShopRepairIndex.vue';

const props = defineProps({
    workshop:{
        type:Object,
        required:true
    },
    workshop_repairs:{
        type:Object,
        required:true
    },
    filters:{
        type:Object,
        required:true
    }
});

const edit = () => {
    router.get(route('workshops.edit', props.workshop.id));
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

const deleteWorkShop = () => {
    formDestroy.delete(route('workshops.destroy',props.workshop.id), {
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
<Head title="WorkShop Show" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="route('workshops.index')">Talleres</Link>
                <span class="text-red-500 font-medium">/</span> {{props.workshop.name}}
            </h1>
        </template>
        
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="workshop.deleted_at != null" class="space-y-4">
                    <RestoreMessage :permission="hasPermission('restaurar taller')" @restore="router.patch(route('workshops.restore',props.workshop.id))">
                        Este Taller esta eliminado, si lo restauras podras acceder y editarlo nuevamente.
                    </RestoreMessage>
                </div>
                <CardSection class="max-w-7xl py-4">
                    <div class="flex flex-wrap my-12">
                        <div class="mx-auto">
                            <span class="inline text-3xl h-fit">{{ props.workshop.name }}</span>
                            <div class="mt-2 text-lg">
                                <span class="font-semibold">Correo:</span> {{ props.workshop.email }}
                                <br>
                                <span class="font-semibold">Telefono:</span> {{ props.workshop.telephone }}
                                <br>
                                <span class="font-semibold">Address:</span> {{ props.workshop.address }}
                                <br>
                                <span class="font-semibold">Departamento:</span> {{ props.workshop.state.name}}
                                <br>
                                <span class="font-semibold">Municipio:</span> {{ props.workshop.town.name}}
                                <br>
                                <span class="font-semibold">Distrito:</span> {{ props.workshop.district.name}}
                                <br>
                                <span class="font-semibold">Creado:</span> {{ props.workshop.created_at }}
                                <br>
                                <span class="font-semibold">Actualizado:</span> {{ props.workshop.updated_at }}
                                <br>
                                <span v-if="props.workshop.deleted_at" class="font-semibold">Eliminado el:</span> {{props.workshop.deleted_at}}
                            </div>
                        </div>
                        <div class="mx-auto items-center text-center">
                            <div class="p-5">
                                <div>
                                    <PrimaryButton v-if="hasPermission('editar taller') && !workshop.deleted_at" class="ml-8" @click="edit()">
                                        Editar
                                    </PrimaryButton>
                                </div>
                                <div>
                                    <DangerButton v-if="hasPermission('eliminar taller') && !props.workshop.deleted_at" class="mt-2 ml-8" @click="confirmDestroy()" >
                                        Eliminar
                                    </DangerButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardSection>
            </div>    
        </div>

        <div class="py-4">
            <WorkShopRepairIndex :workShopId="props.workshop.id" :repairs="props.workshop_repairs" :filters="props.filters" 
            :deleted="props.workshop.deleted_at ? true: false"/>
        </div>

    </AuthenticatedLayout>
    <Modal :show="comfirmingDestroy" @close="closeModalDestroy">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que quieres eliminar es Taller?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez eliminado este taller, permanecer en la base de datos pero no se podra acceder a el,
                    hasta que se restaure o elimine permanentemente, Esta seguro de que quiere eliminar este taller ?
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Contraseña" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="formDestroy.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        @keyup.enter="deleteWorkShop"
                    />

                    <InputError :message="formDestroy.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalDestroy"> Cancelar </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': formDestroy.processing }"
                        :disabled="formDestroy.processing"
                        @click="deleteWorkShop"
                    >
                        Eliminar Taller
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>