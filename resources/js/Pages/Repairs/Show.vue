<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link, usePage} from '@inertiajs/vue3';
import { ref, computed, nextTick } from 'vue';
import CardSection from '@/Components/CardSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
const props = defineProps({
    repair:{
        type:Object,
        required:true
    },
    repair_status:{
        type:Object,
        required:true
    },
})
// change status modal
const formRepairStatus = useForm({
    repair_status_id: props.repair?.repair_status_id ?? '',
});
const comfirmingStatusUpdate = ref(false);
const confirmUpdateStatus = () => comfirmingStatusUpdate.value = true;

const closeModalStatus = () => {
    comfirmingStatusUpdate.value = false;
    formRepairStatus.clearErrors();
};
const updateStatus = () => {
    formRepairStatus.patch(route('repairs.updateStatus', props.repair.id),{
        preserveScroll: true,
        onSuccess: () => {
            closeModalStatus();
        },
        onFinish: () => (formRepairStatus.reset()),
    });
};
const editRepair = () => {
    router.get(route('repairs.edit', props.repair.id));
}

const permissions = ref(usePage().props.auth.user_permissions);
const hasPermission = (permissionName) => {
    return computed(() => permissions.value.includes(permissionName)).value;
};

//file links
const fileDestroy = (id) => {
    router.delete(route('repairs.destroyFile', {repair: props.repair.id, file: id}));
}
//pdf link
const downloadUrl = ref('');

// Adjust this logic to generate the download link based on your requirements
downloadUrl.value = `/repairs/${props.repair.id}/pdf`;
</script>

<template>
    <Head title="Repair Show" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="props.repair.car?.id == null ? route('cars.index'): route('cars.show',props.repair.car.id)">{{repair.car?.model.name ?? 'Volver a Autos'}}</Link>
                <span class="text-red-500 font-medium">/</span> Reparacion #{{props.repair.id}}
            </h1>
        </template>

        <div class="py-9">
            <CardSection class=" mx-auto">
                <div class="flex flex-wrap my-12">
                    <div class="mx-auto pt-12 mb-4">
                        <span class="inline text-3xl h-fit"
                        :class="{'text-red-600 dark:text-red-400' : repair.car == null}"
                        >{{ repair.car?.model?.brand.name ?? 'Auto Actual Eliminado' }}, {{repair.car?.model.name ?? ''}}</span>
                        <div class="mt-2 text-lg">
                            <span class="font-semibold">Año:</span> {{ repair.car?.year ?? ''}}
                            <br>
                            <span class="font-semibold">Placas:</span> {{ repair.car?.plates ?? ''}}
                            <br>
                            <span class="font-semibold">Millaje:</span> {{ repair.car?.current_mileage ?? ''}}
                            <br>
                            <span class="font-semibold">Total:</span> ${{repair.total}}
                            <br>
                            <span class="font-semibold">Creado:</span> {{ repair.created_at }}
                            <br>
                            <span class="font-semibold">Actualizado:</span> {{ repair.updated_at }}
                        </div>
                        <div class="flex flex-wrap mt-2 space-x-2 space-y-2 justify-center">
                            <div>
                                <PrimaryButton class="ml-2 mt-2" v-if="hasPermission('editar reparacion') && (repair.car != null && repair.work_shop != null)" @click="editRepair()">
                                    Editar
                                </PrimaryButton>
                            </div>
                            <div>
                                <SecondaryButton  v-if="hasPermission('editar status reparacion') && (repair.car != null && repair.work_shop != null)"  @click="confirmUpdateStatus()">
                                    Cambiar Estado 
                                </SecondaryButton>
                            </div>
                            <div>
                                <a :href="downloadUrl" v-if="(repair.car != null && repair.work_shop != null)"
                                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    PDF
                                </a>
                            </div>
                           
                        </div>
                    </div>
                    <div class="mx-auto ">
                        <span class="inline text-3xl h-fit"
                        :class="{'text-red-600 dark:text-red-400' : repair.work_shop == null}"
                        >{{ repair.work_shop?.name ?? 'Taller Actualmente Eliminado' }}</span>
                        <div class="mt-2 text-lg">
                            <span class="font-semibold">Correo:</span> {{ repair.work_shop?.email }}
                            <br>
                            <span class="font-semibold">Telefono:</span> {{ repair.work_shop?.telephone }}
                            <br>
                            <span class="font-semibold">Direccion:</span> {{ repair.work_shop?.address}}
                            <br>
                            <span class="font-semibold">Zona: </span> {{ repair.work_shop?.state.name }}, {{ repair.work_shop?.town.name }}, {{ repair.work_shop?.district.name }} 
                            <br>
                        </div>
                        <div class="flex flex-wrap space-x-4 mt-2 p-2 text-xl font-semibold ">
                            <span class="flex w-3 h-3 mt-2 me-3 bg-green-500 rounded-full"
                                :class="{
                                    'bg-green-500': repair.repair_status_id == 1 || repair.repair_status_id == 3,
                                    'bg-yellow-500': repair.repair_status_id == 5,
                                    'bg-red-500': repair.repair_status_id == 2 || repair.repair_status_id == 4,
                                }"
                            ></span>{{repair.status.name}}
                        </div>
                        <div class="mx-auto mt-4">
                            <div class="flex justify-between items-center flex-wrap">
                                <span class="inline text-3xl h-fit" >Documentos</span>
                                <Link  as="button" method="get"  :href="route('repairs.createFile',props.repair.id)"
                                    v-if="hasPermission('subir archivos reparacion') && (repair.car != null && repair.work_shop != null)"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                >
                                    + Archivos   
                                </Link>
                            </div>
                            <div v-if="repair.files.length != 0 ">
                                <div class="flex flex-wrap gap-4 mt-2" >
                                    <div class="">
                                        <div v-for="file in repair.files" :key="file.id" class="flex flex-row mt-4 space-y-2">
                                            <div class="flex items-start gap-2.5">
                                                <div class="flex flex-col gap-1 max-w-full">
                                                    <div class="flex flex-col leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-e-xl rounded-es-xl dark:bg-gray-700">
                                                        <p class="text-sm font-normal text-gray-900 dark:text-white"> {{ file.original_name }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex-row">
                                                    <a :href="route('repairs.downloadFile',{repair:repair.id, file:file.id})"
                                                    v-if="hasPermission('descargar archivo reparacion') && (repair.car != null && repair.work_shop != null)"
                                                    class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600">
                                                        <svg fill="#000000" class="icon line-color w-6 h-6" viewBox="0 0 24 24" id="download-alt" data-name="Line Color" xmlns="http://www.w3.org/2000/svg" ><polyline id="secondary" points="8 12 12 16 16 12" style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polyline><line id="secondary-2" data-name="secondary" x1="12" y1="3" x2="12" y2="16" style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line></svg>
                                                    </a>
                                                    <button @click="fileDestroy(file.id)" v-if="hasPermission('eliminar archivo reparacion') && (repair.car != null && repair.work_shop != null)"
                                                        class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600" type="button">
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
                            <div v-else>
                                <p class="mt-2 text-lg text-gray-500 dark:text-gray-400">No hay archivos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </CardSection>
        </div>
       
        <div class="py-4">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-3xl font-semibold tex-gray-900 dark:text-gray-200">Detalles de la Reparacion.</h2>
                </div>

                <div class="flex flex-wrap justify-between gap-2 my-12 overflow-auto" style="height: 40rem;">
                    <CardSection v-for="detail in props.repair.details" :key="detail.id">
                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <svg class="w-7 h-7 text-gray-500 dark:text-gray-400 mb-3"  fill="currentColor" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 277.766 277.766" style="enable-background:new 0 0 277.766 277.766;" xml:space="preserve">
                                <path d="M273.451,0c-11.894,0.166-45.084,2.314-54.93,20.76c-4.573,8.57-6.146,16.638-6.339,23.356l-79.775,79.776l-24.468-24.468
                                l-93.358,93.358C5.178,202.186,0,214.688,0,227.984c0,13.297,5.178,25.798,14.581,35.2c9.401,9.403,21.903,14.581,35.2,14.581
                                s25.798-5.178,35.2-14.581l92.793-92.792l0.566-0.567l-24.467-24.467l79.775-79.775c6.719-0.192,14.786-1.765,23.355-6.339
                                c18.446-9.846,20.595-43.037,20.761-54.93L273.451,0z"/>
                            </svg>

                            <div class="p-5">
                                <div class="mt-2 text-lg">
                                    <span class="font-semibold">Nombre:</span> {{ detail.name }}
                                    <br>
                                    <span class="font-semibold">Descripcion:</span> {{ detail.description }}
                                    <br>
                                    <span class="font-semibold">Precio:</span> ${{ detail.price }}
                                </div>
                            </div>
                        </div>
                    </CardSection>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <Modal :show="comfirmingStatusUpdate" @close="closeModalStatus">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Quieres Cambiar el Estado?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Este proceso cambiara el estado actual de la Reparacion,de verdad quieres continuar ???.
            </p>

            <div class="mt-6">
                <InputLabel for="repair_status_id" value="Status" />
                <select 
                    id="repair_status_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"
                    v-model="formRepairStatus.repair_status_id"
                    required
                >
                    <option v-for="status in props.repair_status" :value="status.id" :key="status.id">
                        {{status.name}}
                    </option>
                </select>

                <InputError class="mt-2" :message="formRepairStatus.errors.repair_status_id" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModalStatus"> Cancelar </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': formRepairStatus.processing }"
                    :disabled="formRepairStatus.processing"
                    @click="updateStatus"
                >
                    Guardar
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>