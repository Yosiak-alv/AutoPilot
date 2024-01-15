<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import { ref, nextTick, computed } from 'vue';
import Slider from '@/Components/Slider.vue';
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
    images: [],
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

//file links
const fileDestroy = (id) => {
    router.delete(route('cars.destroyFile', {car: props.car.id, file: id}));
}
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
            </div>

            <div class="flex flex-wrap justify-between gap-4 my-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mx-auto">
                    <div id="car_card" class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 text-gray-900 dark:text-gray-100">
                        <div class="rounded-lg">
                            <Slider class="rounded-lg" :carImages="car.images"/>
                        </div>
                        <div class="p-5">
                            <span class="inline text-3xl h-fit">{{ car.model.brand.name}}, {{car.model.name}}</span>
                            <div class="mt-2 text-lg">
                                <span class="font-semibold">Millaje:</span> {{ car.current_mileage }}
                                <br>
                                <span class="font-semibold">Placas:</span> {{ car.plates }}
                                <br>
                                <span class="font-semibold">VIN:</span> {{ car.VIN }}
                                <br>
                                <span class="font-semibold">Motor:</span> {{ car.motorId }}
                                <br>
                                <span class="font-semibold">Color:</span> {{ car.color }}
                                <br>
                                <span class="font-semibold">Año:</span> {{ car.year }}
                                <br>
                                <span class="font-semibold">Creado:</span> {{ car.created_at }}
                                <br>
                                <span class="font-semibold">Actualizado:</span> {{ car.updated_at }}
                                <br>
                                <span v-if="props.car.deleted_at" class="font-semibold">Eliminado el:</span> {{props.car.deleted_at}}
                            </div>
                            <div class="flex flex-wrap space-x-4  space-y-1 mt-2">
                                <PrimaryButton @click="edit()" v-if="hasPermission('editar auto') && car.deleted_at == null" class="w-12/9">
                                    Editar
                                </PrimaryButton>
                                <SecondaryButton @click="confirmStoreImage()" v-if="hasPermission('agregar imagenes auto') && car.deleted_at == null" class="w-12/9">
                                    + Imagenes
                                </SecondaryButton>
                                <DangerButton @click="confirmDestroy()"  v-if="hasPermission('eliminar auto') && car.deleted_at == null" class="w-12/9 ">
                                    Eliminar
                                </DangerButton>
                                <a :href="route('cars.excelRepairsExport',props.car.id)" v-if="hasPermission('exportar a excel') && props.car_repairs.data.length != 0"                       
                                    class="inline-flex items-center px-4 py-2 bg-green-500  border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-400  focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Excel Reparaciones
                                </a>
                            </div>
                        </div>
                    </div>
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
                    <div class="mx-auto mt-12">
                        <CardSection>
                            <div class="p-5">
                                <div class="flex justify-between items-center flex-wrap">
                                    <span class="inline text-3xl h-fit" >Documentos</span>
                                    <Link :href="route('cars.createFile',{id: car.id})" as="button" method="get"  
                                        v-if="hasPermission('subir archivos auto') && car.deleted_at == null"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                    >
                                        + Archivos   
                                    </Link>
                                </div>
                                <div v-if="car.files.length != 0 ">
                                    <div class="flex flex-wrap gap-4 mt-2" >
                                        <div class="overflow-y-auto " style="height: 12rem;">
                                            <div class="flex-col items-center">
                                                <div v-for="file in car.files" :key="file.id" class="mt-4 space-y-2">
                                                    <div class="flex items-start gap-2.5">
                                                        <div class="flex flex-col gap-1 max-w-full">
                                                            <div class="flex flex-col leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-e-xl rounded-es-xl dark:bg-gray-700">
                                                                <p class="text-sm font-normal text-gray-900 dark:text-white"> {{ file.original_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex-col">
                                                            <a :href="route('cars.downloadFile', {car: car.id, file: file.id})" 
                                                            v-if="hasPermission('descargar archivo auto') && car.deleted_at == null" class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600">
                                                                <svg fill="#000000" class="icon line-color w-6 h-6" viewBox="0 0 24 24" id="download-alt" data-name="Line Color" xmlns="http://www.w3.org/2000/svg" ><polyline id="secondary" points="8 12 12 16 16 12" style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polyline><line id="secondary-2" data-name="secondary" x1="12" y1="3" x2="12" y2="16" style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line></svg>
                                                            </a>
                                                            <button v-if="hasPermission('eliminar archivo auto') && car.deleted_at == null" 
                                                            @click="fileDestroy(file.id)" class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600" type="button">
                                                                <svg fill="red" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                                                        class="w-6 h-6" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                                                    <g>
                                                                        <g>
                                                                            <path d="M75.834,33.388h-51.67c-1.311,0-2.375,1.058-2.375,2.373v49.887c0,1.314,1.064,2.377,2.375,2.377h51.67
                                                                                c1.314,0,2.375-1.063,2.375-2.377V35.76C78.209,34.446,77.148,33.388,75.834,33.388z"/>
                                                                        </g>
                                                                        <g>
                                                                            <path d="M79.004,17.352H59.402v-2.999c0-1.314-1.061-2.377-2.373-2.377H42.971c-1.312,0-2.375,1.063-2.375,2.377v2.999H20.996
                                                                                c-1.312,0-2.375,1.059-2.375,2.373v6.932c0,1.314,1.063,2.373,2.375,2.373h58.008c1.314,0,2.375-1.059,2.375-2.373v-6.932
                                                                                C81.379,18.41,80.318,17.352,79.004,17.352z"/>
                                                                        </g>
                                                                    </g>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <p class="mt-2 text-lg text-gray-500 dark:text-gray-400">No hay archivos</p>
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
                <input @input="formImage.images = $event.target.files" id="image" multiple type="file" accept="image/png, image/jpg, image/jpeg" 
                class="block w-full text-md text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" >
                
                <div v-for="(error, index) in formImage.errors" :key="index">
                    <InputError :message="error" class="mt-2" />
                </div>
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