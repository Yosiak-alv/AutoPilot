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
//---------------------------------------------

const destroy = () => {
    router.delete(route('brands.destroyModel', props.model.id));
}
</script>

<template>
    <Head title="Model Show" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Marca #{{props.model.id}}</h2>
        </template>
        
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-wrap my-12">
                    <div class="mx-auto">
                        <CardSection>
                            <div class="grid grid-cols-4 gap-4 p-6 items-center">
                                <div class="col-span-3">
                                    <span class="inline text-3xl h-fit">{{ props.model.name }}</span>
                                </div>
                                <div class="col-span-1 justify-items-center">
                                    <div>
                                        <PrimaryButton class="ml-8" @click="confirmModelEdition()">
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
</template>