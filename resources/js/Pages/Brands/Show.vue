<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link} from '@inertiajs/vue3';
import { ref } from 'vue';
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
    formBrand.reset();
    formBrand.clearErrors();
};
const editBrand = () => {
    formBrand.patch(route('brands.update', props.brand.id), {
        preserveScroll: true,
        onSuccess: () => closeModalBrand(),
        /* onError: () => console.log('error'), */
        onFinish: () => (formBrand.reset()),
    });
}
//---------------------------------------------

const destroy = () => {
    router.delete(route('brands.destroy', props.brand.id));
}
</script>

<template>
    <Head title="Brand Show" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Marca #{{props.brand.id}}</h2>
        </template>
        
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-wrap my-12">
                    <div class="mx-auto">
                        <CardSection>
                            <div class="grid grid-cols-4 gap-4 p-6 items-center">
                                <div class="col-span-3">
                                    <span class="inline text-3xl h-fit">{{ props.brand.name }}</span>
                                    <div class="mt-2 text-lg">
                                        <span class="font-semibold">Creado:</span> {{ props.brand.created_at }}
                                        <br>
                                        <span class="font-semibold">Actualizado:</span> {{ props.brand.updated_at }}
                                    </div>
                                </div>
                                <div class="col-span-1 justify-items-center">
                                    <div>
                                        <PrimaryButton class="ml-8" @click="confirmBrandEdition()">
                                            Editar
                                        </PrimaryButton>
                                    </div>
                                    <div>
                                        <DangerButton class="mt-2 ml-8" @click="destroy()" >
                                            Eliminar Centro
                                        </DangerButton>
                                    </div>
                                </div>
                            </div>
                        </CardSection>
                    </div>
                </div>
            </div>    
        </div>
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
</template>