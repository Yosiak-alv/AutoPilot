<script setup>
import CardSection from '@/Components/CardSection.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { ref } from 'vue';
const props = defineProps({
    brandId:{
        type:Number,
        required:true
    },
    models:{
        type:Object,
        required:true
    }
});
// Modal add Models
const formModel = useForm({
    name: '',
    brand_id: props.brandId,
});
const confirmingModelCreation = ref(false);
const confirmModelCreation = () => confirmingModelCreation.value = true;
const closeModalModel = () => {
    confirmingModelCreation.value = false;
    formModel.reset();
    formModel.clearErrors();
};
const createModel = () => {
    formModel.post(route('brands.storeModel',props.brandId), {
        preserveScroll: true,
        onSuccess: () => closeModalModel(),
        /* onError: () => console.log('error'), */
        onFinish: () => (formModel.reset()),
    });
}
</script>

<template>
    <div v-if="props.models.length != 0 ">
        <div class="max-w-7xl mx-auto text-center my-4 text-gray-900 dark:text-gray-100">
            <h2 class="text-3xl font-semibold">Modelos</h2>
            <div class="flex justify-between items-center p-2 mt-4">
                <div>
                    <PrimaryButton @click="confirmModelCreation()">
                        + Modelo
                    </PrimaryButton>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap justify-between gap-4 my-12">
            <CardSection v-for="model in props.models" :key="model.id" class="text-base p-2">
                <Link method="get" as="button" :href="route('brands.showModel',model.id)" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{model.name}}</h5>
                </Link>
            </CardSection>
        </div>

    </div>
    <div v-else>
        <div class="max-w-7xl mx-auto text-center my-4 text-gray-900 dark:text-gray-100">
            <h2 class="text-3xl font-semibold">No hay Modelos para esta Marca.</h2>
            <div class="flex justify-between p-2 mt-4">
                <div>
                    <PrimaryButton @click="confirmModelCreation()">
                        + Modelo
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </div>
    <Modal :show="confirmingModelCreation " @close="closeModalModel">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Crear Nuevo Modelo
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
                    @click="createModel()"
                >
                    Guardar
                </PrimaryButton>
            </div>
        </div>
    </Modal>

</template>