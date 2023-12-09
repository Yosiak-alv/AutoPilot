<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import { ref, computed, nextTick } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    model:{
        type:Object,
        required:true
    },
});

// Modal edit brand
const formModel = useForm({
    name: props.model?.name,
});
const comfirmingModelEdition = ref(false);
const confirmModelEdition = () => comfirmingModelEdition.value = true;
const closeModalModel = () => {
    comfirmingModelEdition.value = false;
    formModel.reset();
    formModel.clearErrors();
};
const editModel = () => {
    formModel.patch(route('brands.updateModel', props.model.id), {
        preserveScroll: true,
        onSuccess: () => closeModalModel(),
        /* onError: () => console.log('error'), */
        onFinish: () => (formModel.reset()),
    });
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

const deleteModel = () => {
    formDestroy.delete(route('brands.destroyModel',props.model.id), {
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
    <Head title="Model Show" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Marca #{{props.model.id}}</h2>
        </template>
        <CardSection class="max-w-2xl py-12">
            <div class="flex flex-wrap my-12">
                <div class="mx-auto">
                    <div class="mt-2 text-lg">
                        <span class="inline text-3xl h-fit">{{ props.model.name }}</span>
                    </div>
                </div>
                <div class="mx-auto items-center text-center">
                    <div class="p-5">
                        <div>
                            <PrimaryButton class="ml-8" @click="confirmModelEdition()" v-if="hasPermission('editar modelo')">
                                Editar
                            </PrimaryButton>
                        </div>
                        <div>
                            <DangerButton class="mt-2 ml-8" @click="confirmDestroy()" v-if="hasPermission('eliminar modelo')">
                                Eliminar 
                            </DangerButton>
                        </div>
                    </div>
                </div>
            </div>
        </CardSection>
    </AuthenticatedLayout>

    <Modal :show="comfirmingModelEdition" @close="closeModalModel">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Actualizar Modelo
            </h2>

            <div class="mt-6">
                <InputLabel for="name" value="Nombre" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="formModel.name"
                    required
                />
                <InputError :message="formModel.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModalModel"> Cancelar </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': formModel.processing }"
                    :disabled="formModel.processing"
                    @click="editModel()"
                >
                    Actualizar
                </PrimaryButton>
            </div>
        </div>
    </Modal>
    <Modal :show="comfirmingDestroy" @close="closeModalDestroy">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que quieres eliminar este Modelo
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez eliminado este auto, todos los registros y datos se perderán permanentemente.
                    Estas seguro que quieres eliminar este modelo ?
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
                        @click="deleteModel"
                    >
                        Eliminar Modelo
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>