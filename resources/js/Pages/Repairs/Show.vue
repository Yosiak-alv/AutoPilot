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
    formRepairStatus.reset();
    formRepairStatus.clearErrors();
};
const updateStatus = () => {
    formRepairStatus.patch(route('repairs.updateStatus', props.repair.id),{
        preserveScroll: true,
        onSuccess: () => closeModalStatus(),
        /* onError: () => passwordInput.value.focus(), */
        onFinish: () => (formRepairStatus.reset())
    });
};
const editRepair = () => {
    router.get(route('repairs.edit', props.repair.id));
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
const closeModalDestroy = () => {
    comfirmingDestroy.value = false;
    formDestroy.reset();
    formDestroy.clearErrors();
};
const deleteRepair = () => {
    formDestroy.delete(route('repairs.destroy',props.repair.id), {
        preserveScroll: true,
        onSuccess: () => closeModalDestroy(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => formDestroy.reset(),
    });
};

const permissions = ref(usePage().props.auth.user_permissions);
const hasPermission = (permissionName) => {
    return computed(() => permissions.value.includes(permissionName)).value;
};
</script>

<template>
    <Head title="Repair Show" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                <Link class="text-red-500 hover:text-red-700 hover:underline" :href="route('cars.show',props.repair.car.id)">{{repair.car?.model.name ?? 'Auto'}}</Link>
                <span class="text-red-500 font-medium">/</span> Reparacion #{{props.repair.id}}
            </h1>
        </template>

        <div class="py-9">
            <CardSection class="max-w-7xl mx-auto">
                <div class="flex flex-wrap my-12">
                    <div class="mx-auto">
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
                            <span class="font-semibold">Estado:</span> {{repair.status.name}}
                            <br>
                            <span class="font-semibold">Sub Total:</span> ${{repair.sub_total}}
                            <br>
                            <span class="font-semibold">Impustos:</span> ${{repair.taxes}}
                            <br>
                            <span class="font-semibold">Total:</span> ${{repair.total}}
                            <br>
                            <span class="font-semibold">Creado:</span> {{ repair.created_at }}
                            <br>
                            <span class="font-semibold">Actualizado:</span> {{ repair.updated_at }}
                        </div>
                        <div class="flex space-x-4 mt-2 p-2">
                            <div>
                                <PrimaryButton v-if="hasPermission('editar reparacion') && (repair.car != null && repair.work_shop != null)" @click="editRepair()">
                                    Editar
                                </PrimaryButton>
                            </div>
                            <div>
                                <SecondaryButton  v-if="hasPermission('editar status reparacion') && (repair.car != null && repair.work_shop != null)"  @click="confirmUpdateStatus()">
                                    Cambiar Estado 
                                </SecondaryButton>
                            </div>
                            <div >
                                <DangerButton v-if="hasPermission('eliminar reparacion') && (repair.car != null && repair.work_shop != null)" @click="confirmDestroy()"  >
                                    Eliminar 
                                </DangerButton>
                            </div>
                        </div>
                    </div>
                    <div class="mx-auto">
                        <span class="inline text-3xl h-fit"
                        :class="{'text-red-600 dark:text-red-400' : repair.work_shop == null}"
                        >{{ repair.work_shop?.name ?? 'Taller Actualmente Eliminado' }}</span>
                        <div class="mt-2 text-lg">
                            <span class="font-semibold">Correo:</span> {{ repair.work_shop?.email }}
                            <br>
                            <span class="font-semibold">Telefono:</span> {{ repair.work_shop?.telephone }}
                            <br>
                            <span class="font-semibold">Estado:</span> {{repair.status.name}}
                            <br>
                            <span class="font-semibold">Direccion:</span> {{ repair.work_shop?.address}}
                            <br>
                            <span class="font-semibold">Zona: </span> {{ repair.work_shop?.state.name }}, {{ repair.work_shop?.town.name }}, {{ repair.work_shop?.district.name }} 
                            <br>
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
                                    <br>
                                    <span class="font-semibold">Impuestos:</span> ${{ detail.taxes }}
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
    <Modal :show="comfirmingDestroy" @close="closeModalDestroy">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    ¿Seguro que quieres eliminar esta Reparacion?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Una vez eliminado este auto, todos sus recursos y datos se eliminaran permanentemente. 
                    Este proceso no se puede deshacer, estas seguro de querer continuar?
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
                        @click="deleteRepair"
                    >
                        Eliminar Auto
                    </DangerButton>
                </div>
            </div>
        </Modal>
</template>