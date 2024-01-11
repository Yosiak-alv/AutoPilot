<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import { ref, nextTick, computed } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import CarRepairIndex from './Partials/CarRepairsIndex.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import RestoreMessage from '@/Components/RestoreMessage.vue';

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

const deleteCar = () => {
    formDestroy.delete(route('cars.destroy',props.car.id), {
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
    <Head title="Car Show" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="route('cars.index')">Autos</Link>
                <span class="text-red-500 font-medium">/</span> {{car.model.name}}
            </h1>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="car.deleted_at != null" class="space-y-4">
                    <RestoreMessage :permission="hasPermission('restaurar auto')" @restore="router.patch(route('cars.restore',props.car.id))">
                        Este auto esta eliminado, si lo restauras podras acceder y editarlo nuevamente.
                    </RestoreMessage>
                </div>

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
                                        <br>
                                        <span v-if="props.car.deleted_at" class="font-semibold">Eliminado el:</span> {{props.car.deleted_at}}
                                    </div>
                                    <div class="flex space-x-4 mt-2">
                                        <PrimaryButton @click="edit()" v-if="hasPermission('editar auto') && car.deleted_at == null" class="w-12/9">
                                            Editar
                                        </PrimaryButton>
                                        <SecondaryButton @click="confirmStoreImage()" v-if="hasPermission('agregar imagen auto') && car.deleted_at == null" class="w-12/9">
                                            + Imagen
                                        </SecondaryButton>
                                        <DangerButton @click="confirmDestroy()"  v-if="hasPermission('eliminar auto') && car.deleted_at == null" class="w-12/9">
                                            Eliminar
                                        </DangerButton>
                                    </div>
                                </div>
                            </div>
                        </CardSection>
                    </div>
                    <div class="mx-auto">
                        <CardSection>
                            <div class="p-5">
                                    <span class="inline text-3xl h-fit" :class="{'text-red-600 dark:text-red-400' : car.branch  == null}">
                                    {{ car.branch?.name ?? 'Sin Centro Asignado'}}</span>
                                    <div class="mt-2 text-lg" v-if="car.branch">
                                        <span class="font-semibold">Correo:</span> {{ car.branch?.email }}
                                        <br>
                                        <span class="font-semibold">Telefono:</span> {{ car.branch?.telephone }}
                                        <br>
                                        <span class="font-semibold">Es Central:</span> {{ car.branch?.main ? 'Si':'No' }}
                                        <br>
                                        <span class="font-semibold">Direccion:</span> {{ car.branch?.address }}
                                        <br>
                                        <span class="font-semibold">Zona:</span> {{car.branch?.district.town.state.name}}, {{car.branch?.district.town.name}}, {{ car.branch?.district.name }}
                                    </div>
                                </div>
                        </CardSection>
                       
                    </div>
                    
                </div>
            </div>    
        </div>

        <div class="py-4">
           <CarRepairIndex :repairs="car_repairs" :filters="filters" :carId="props.car.id" :deleted="props.car.deleted_at  ? true: false" />
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
                        @click="deleteCar"
                    >
                        Eliminar Auto
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>