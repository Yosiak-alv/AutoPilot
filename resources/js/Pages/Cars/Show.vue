<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link} from '@inertiajs/vue3';
import { ref } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import CarRepairIndex from './Partials/CarRepairsIndex.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    car:{
        type:Object,
        required:true
    },
    carImageUrl:{
        type:String,
        required:true
    },
    car_repairs:{
        type:Object,
        required:true
    },
    filters:{
        type:Object,
        required:true
    },
});

const edit = () => {
    router.get(route('cars.edit', props.car.id));
}
const destroy = () => {
    router.delete(route('cars.destroy', props.car.id));
}
//Image Modal
const comfirmingImageStoring = ref(false);
const confirmStoreImage = () => comfirmingImageStoring.value = true;
const formImage = useForm({
    image: null,
});

const closeModalImage = () => {
    comfirmingImageStoring.value = false;
    formImage.reset();
    formImage.clearErrors();
};
const storeImage = () => {
    formImage.post(route('cars.storeImage', props.car.id),{
        preserveScroll: true,
        onSuccess: () => closeModalImage(),
        /* onError: () => passwordInput.value.focus(), */
        onFinish: () => (formImage.reset()),
    });
}
</script>

<template>
    <Head title="Car Show" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Auto {{car.model.name}}</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-wrap justify-between gap-2 my-12">
                    <div class="mx-auto">
                        <CardSection>
                            <div class="max-w-md bg-whiterounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <img class="rounded-t-lg" :src="carImageUrl" alt="Car" />
                                <div class="p-5">
                                    <span class="inline text-3xl h-fit">{{ car.model.brand.name}}, {{car.model.name}}</span>
                                    <div class="mt-2 text-lg">
                                        <span class="font-semibold">Millaje:</span> {{ car.current_mileage }}
                                        <br>
                                        <span class="font-semibold">Placas:</span> {{ car.plates }}
                                        <br>
                                        <span class="font-semibold">VIN:</span> {{ car.VIN }}
                                        <br>
                                        <span class="font-semibold">Año:</span> {{ car.year }}
                                        <br>
                                        <span class="font-semibold">Creado:</span> {{ car.created_at }}
                                        <br>
                                        <span class="font-semibold">Actualizado:</span> {{ car.updated_at }}
                                    </div>
                                    <div class="flex space-x-4 mt-2">
                                        <PrimaryButton @click="edit()" class="w-12/9">Editar</PrimaryButton>
                                        <SecondaryButton @click="confirmStoreImage()" class="w-12/9">+ Imagen</SecondaryButton>
                                        <DangerButton @click="destroy()" class="w-12/9">Eliminar</DangerButton>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </CardSection>
                    </div>
                    <div class="mx-auto">
                        <CardSection>
                            <div class="p-5">
                                    <span class="inline text-3xl h-fit">{{ car.branch.name}}</span>
                                    <div class="mt-2 text-lg">
                                        <span class="font-semibold">Correo:</span> {{ car.branch.email }}
                                        <br>
                                        <span class="font-semibold">Telefono:</span> {{ car.branch.telephone }}
                                        <br>
                                        <span class="font-semibold">Direccion:</span> {{ car.branch.address }}
                                        <br>
                                        <span class="font-semibold">Es Central:</span> {{ car.branch.main ? 'Si':'No' }}
                                        <br>
                                    </div>
                                </div>
                        </CardSection>
                       
                    </div>
                    
                </div>
            </div>    
        </div>

        <div class="py-4">
           <CarRepairIndex :repairs="car_repairs" :filters="filters" :carId="props.car.id" />
        </div>
    </AuthenticatedLayout>


    <Modal :show="comfirmingImageStoring" @close="closeModalImage">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Quieres Cambiar la Imagen?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Este proceso cambiara la actual imagen del auto, lo que eliminara la imagen actual 
                y se remplazara con la que subas, de verdad quieres continuar ???.
            </p>

            <div class="mt-6">
                <input @input="formImage.image = $event.target.files[0]" id="image" type="file" accept="image/png, image/jpg, image/jpeg" 
                class="block w-full text-md text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" >

                <InputError :message="formImage.errors.image" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModalImage"> Cancelar </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': formImage.processing }"
                    :disabled="formImage.processing"
                    @click="storeImage"
                >
                    Guardar
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>