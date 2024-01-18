<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import { ref, nextTick,computed } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ModelIndex from './ModelIndex.vue';
const props = defineProps({
    brand:{
        type:Object,
        required:true
    },
});

// Modal edit brand
const formBrand = useForm({
    name: props.brand?.name,
});
const comfirmingBrandEdition = ref(false);
const confirmBrandEdition = () => comfirmingBrandEdition.value = true;
const closeModalBrand = () => {
    comfirmingBrandEdition.value = false;
    formBrand.clearErrors();
};
const editBrand = () => {
    formBrand.patch(route('brands.update', props.brand.id), {
        preserveScroll: true,
        onSuccess: () => closeModalBrand(),
        onError: () => {},
        onFinish: () => (formBrand.reset()),
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

const deleteBrand = () => {
    formDestroy.delete(route('brands.destroy',props.brand.id), {
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
    <Head title="Brand Show" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="route('brands.index')">Marcas</Link>
                <span class="text-red-500 font-medium">/</span>{{props.brand.name}}
            </h1>
        </template>
        <CardSection class="max-w-4xl py-12">
            <div class="flex flex-wrap my-12">
                <div class="mx-auto">
                    <div class="mt-2 text-lg">
                        <span class="inline text-3xl h-fit">{{ props.brand.name }}</span>
                        <div class="mt-2 text-lg">
                            <span class="font-semibold">Creado:</span> {{ props.brand.created_at }}
                            <br>
                            <span class="font-semibold">Actualizado:</span> {{ props.brand.updated_at }}
                        </div>
                    </div>
                </div>
                <div class="mx-auto items-center text-center">
                    <div class="p-5">
                        <div>
                            <PrimaryButton class="ml-8" @click="confirmBrandEdition()" v-if="hasPermission('editar marca')">
                                Editar
                            </PrimaryButton>
                        </div>
                        <div>
                            <DangerButton class="mt-2 ml-8" @click="confirmDestroy()" v-if="hasPermission('eliminar marca')">
                                Eliminar 
                            </DangerButton>
                        </div>
                    </div>
                </div>
            </div>
        </CardSection>
        
        <div class="py-4">
            <ModelIndex :brand-id="props.brand.id" :models="props.brand.models"/>
        </div>
    </AuthenticatedLayout>

    <Modal :show="comfirmingBrandEdition" @close="closeModalBrand">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Actualizar Marca
            </h2>

            <div class="mt-6">
                <InputLabel for="name" value="Nombre" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="formBrand.name"
                    required
                />
                <InputError :message="formBrand.errors.name" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModalBrand"> Cancelar </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': formBrand.processing }"
                    :disabled="formBrand.processing"
                    @click="editBrand()"
                >
                    Actualizar
                </PrimaryButton>
            </div>
        </div>
    </Modal>
    <Modal :show="comfirmingDestroy" @close="closeModalDestroy">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que quieres eliminar es Auto?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez eliminado este auto, permanecer en la base de datos pero no se podra acceder a el,
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
                    />

                    <InputError :message="formDestroy.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalDestroy"> Cancelar </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': formDestroy.processing }"
                        :disabled="formDestroy.processing"
                        @click="deleteBrand"
                    >
                        Eliminar AMarca
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>